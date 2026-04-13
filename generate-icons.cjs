const sharp = require('sharp');
const fs = require('fs');

const sizes = [72, 96, 128, 144, 152, 192, 384, 512];
const inputImage = 'public/logo.svg';

// Créer le dossier icons s'il n'existe pas
if (!fs.existsSync('public/icons')) {
    fs.mkdirSync('public/icons', { recursive: true });
}

// Vérifier si le fichier source existe
if (!fs.existsSync(inputImage)) {
    console.error(`❌ Fichier source non trouvé: ${inputImage}`);
    console.log('📝 Création d\'un logo par défaut...');
    
    // Créer un SVG par défaut
    const defaultSvg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
        <rect width="100" height="100" rx="20" fill="#3b82f6"/>
        <text x="50" y="70" text-anchor="middle" fill="white" font-size="50" font-weight="bold">B</text>
    </svg>`;
    
    fs.writeFileSync(inputImage, defaultSvg);
    console.log(`✅ Logo par défaut créé: ${inputImage}`);
}

console.log('🎨 Génération des icônes PWA...');

sizes.forEach(size => {
    sharp(inputImage)
        .resize(size, size)
        .png()
        .toFile(`public/icons/icon-${size}x${size}.png`)
        .then(() => console.log(`✅ Généré: ${size}x${size}`))
        .catch(err => console.error(`❌ Erreur ${size}x${size}:`, err.message));
});

console.log('📱 Génération terminée !');