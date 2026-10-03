import fs from 'node:fs';
import path from 'node:path';

const routesDirectory = 'resources/js/routes';

// Directories we NEVER touch — Vue pages import exact names from these.
const PROTECTED_DIRS = [
    'verification',
    'password',
];

function findExportBlocks(content) {
    const regex = /^export const (\w+) =/gm;
    const blocks = [];

    let match;
    while ((match = regex.exec(content)) !== null) {
        const start = match.index;
        const nextExport = content.indexOf('\nexport const ', start + 1);
        const end = nextExport !== -1 ? nextExport : content.length;

        blocks.push({
            name: match[1],
            start,
            end,
            content: content.slice(start, end),
        });
    }

    return blocks;
}

function processFile(filePath) {
    const rel = path
        .relative(routesDirectory, filePath)
        .replace(/\\/g, '/');

    for (const dir of PROTECTED_DIRS) {
        if (rel === dir || rel.startsWith(dir + '/')) {
            return;
        }
    }

    let content = fs.readFileSync(filePath, 'utf8');
    const blocks = findExportBlocks(content);

    const grouped = new Map();
    for (const block of blocks) {
        if (!grouped.has(block.name)) {
            grouped.set(block.name, []);
        }
        grouped.get(block.name).push(block);
    }

    const toRemove = [];

    for (const [name, items] of grouped) {
        if (items.length <= 1) continue;
        for (const dup of items.slice(1)) {
            toRemove.push(dup);
        }
    }

    if (toRemove.length === 0) return;

    toRemove.sort((a, b) => b.start - a.start);

    for (const dup of toRemove) {
        content =
            content.slice(0, dup.start) + content.slice(dup.end);

        console.log(
            `Removed duplicate export "${dup.name}" from ${path.relative(process.cwd(), filePath)}`
        );
    }

    fs.writeFileSync(filePath, content);
}

function walk(directory) {
    if (!fs.existsSync(directory)) return;

    for (const entry of fs.readdirSync(directory, {
        withFileTypes: true,
    })) {
        const fullPath = path.join(directory, entry.name);

        if (entry.isDirectory()) {
            walk(fullPath);
        } else if (
            entry.isFile() &&
            entry.name.endsWith('.ts')
        ) {
            processFile(fullPath);
        }
    }
}

walk(routesDirectory);

console.log('Wayfinder duplicate-route patch completed.');