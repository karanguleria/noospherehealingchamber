const fs = require('fs');

try {
    const composerData = JSON.parse(fs.readFileSync('composer_out.json', 'utf16le').replace(/^\uFEFF/, ''));
    console.log('--- COMPOSER PACKAGES ---');
    composerData.installed.forEach(pkg => {
        if (!pkg.name.startsWith('laravel/') && pkg.name !== 'laravel/nova') {
            const current = pkg.version;
            const latest = pkg.latest;
            if (current !== latest && latest) {
              console.log(`${pkg.name}: ${current} -> ${latest}`);
            }
        }
    });
} catch (e) {
    console.log('Error reading composer_out.json:', e.message);
}

try {
    const npmData = JSON.parse(fs.readFileSync('npm_out.json', 'utf16le').replace(/^\uFEFF/, ''));
    console.log('\n--- NPM PACKAGES ---');
    for (const [name, info] of Object.entries(npmData)) {
        const current = info.current;
        const latest = info.latest;
        if (current !== latest && latest) {
            console.log(`${name}: ${current} -> ${latest}`);
        }
    }
} catch (e) {
    console.log('Error reading npm_out.json:', e.message);
}
