import Dexie from 'dexie';

export const db = new Dexie('BarayoroDB');

db.version(1).stores({
    tasks: '++id, uuid, status, priority, due_date, sync_status, updated_at',
    projects: '++id, uuid, status, sync_status, updated_at',
    clients: '++id, uuid, status, sync_status, updated_at',
    products: '++id, uuid, sync_status, updated_at',
    invoices: '++id, uuid, status, sync_status, updated_at',
    users: '++id, uuid, sync_status, updated_at',
    departments: '++id, uuid, sync_status, updated_at',
    pending_operations: '++id, entity_type, entity_uuid, operation, status, created_at',
    sync_metadata: 'key',
});

export default db;
