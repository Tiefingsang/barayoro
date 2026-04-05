import db from '../db';
import axios from 'axios';

class SyncService {
    constructor() {
        this.isOnline = navigator.onLine;
        this.isSyncing = false;
        this.listeners = [];

        window.addEventListener('online', () => this.handleOnline());
        window.addEventListener('offline', () => this.handleOffline());
    }

    handleOnline() {
        console.log('🟢 Connecté - Démarrage synchronisation');
        this.isOnline = true;
        this.notifyListeners('online', {});
        this.sync();
    }

    handleOffline() {
        console.log('🔴 Déconnecté - Mode hors ligne');
        this.isOnline = false;
        this.notifyListeners('offline', {});
    }

    addListener(callback) {
        this.listeners.push(callback);
    }

    notifyListeners(event, data) {
        this.listeners.forEach(callback => callback(event, data));
    }

    async addPendingOperation(entityType, entityUuid, operation, data, oldData = null) {
        await db.pending_operations.add({
            entity_type: entityType,
            entity_uuid: entityUuid,
            operation: operation,
            data: JSON.stringify(data),
            old_data: oldData ? JSON.stringify(oldData) : null,
            status: 'pending',
            created_at: new Date().toISOString(),
            attempts: 0
        });

        this.notifyListeners('pending_changed', { count: await this.getPendingCount() });

        if (this.isOnline && !this.isSyncing) {
            this.sync();
        }
    }

    async getPendingCount() {
        return await db.pending_operations.where('status').equals('pending').count();
    }

    async sync() {
        if (!this.isOnline || this.isSyncing) return;

        this.isSyncing = true;
        this.notifyListeners('sync_started', {});

        try {
            await this.pushChanges();
            await this.pullChanges();
            await this.updateSyncMetadata();

            this.notifyListeners('sync_completed', { success: true });
        } catch (error) {
            console.error('Sync error:', error);
            this.notifyListeners('sync_failed', { error: error.message });
        } finally {
            this.isSyncing = false;
        }
    }

    async pushChanges() {
        const pendingOps = await db.pending_operations.where('status').equals('pending').toArray();
        if (pendingOps.length === 0) return;

        const grouped = {};
        for (const op of pendingOps) {
            if (!grouped[op.entity_type]) grouped[op.entity_type] = [];
            grouped[op.entity_type].push(op);
        }

        for (const [entityType, operations] of Object.entries(grouped)) {
            try {
                const response = await axios.post('/api/sync/push', {
                    entity_type: entityType,
                    operations: operations.map(op => ({
                        uuid: op.entity_uuid,
                        operation: op.operation,
                        data: JSON.parse(op.data),
                        old_data: op.old_data ? JSON.parse(op.old_data) : null
                    }))
                });

                if (response.data.success) {
                    for (const op of operations) {
                        await db.pending_operations.update(op.id, { status: 'synced' });
                    }
                    await db.pending_operations.where('status').equals('synced').delete();
                }
            } catch (error) {
                console.error(`Error pushing ${entityType}:`, error);
            }
        }
    }

    async pullChanges() {
        const lastSync = await this.getLastSyncTimestamp();
        const response = await axios.get('/api/sync/pull', {
            params: { last_sync: lastSync }
        });

        if (response.data.success && response.data.changes) {
            for (const [entityType, entities] of Object.entries(response.data.changes)) {
                await this.applyChanges(entityType, entities);
            }
        }
    }

    async applyChanges(entityType, entities) {
        const table = db[entityType];
        if (!table) return;

        for (const entity of entities) {
            const local = await table.where('uuid').equals(entity.uuid).first();

            if (local && local.updated_at && new Date(local.updated_at) > new Date(entity.updated_at)) {
                await this.addPendingOperation(entityType, entity.uuid, 'update', local);
                continue;
            }

            if (entity.deleted_at) {
                await table.where('uuid').equals(entity.uuid).delete();
            } else {
                await table.put({
                    ...entity,
                    sync_status: 'synced',
                    synced_at: new Date().toISOString()
                });
            }
        }
    }

    async getLastSyncTimestamp() {
        const meta = await db.sync_metadata.where('key').equals('last_sync').first();
        return meta ? meta.value : null;
    }

    async updateSyncMetadata() {
        await db.sync_metadata.put({
            key: 'last_sync',
            value: new Date().toISOString()
        });
    }

    generateUUID() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            const r = Math.random() * 16 | 0;
            const v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }
}

export const syncService = new SyncService();
export default syncService;
