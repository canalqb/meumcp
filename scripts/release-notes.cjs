#!/usr/bin/env node
/**
 * Gerador de Notas da Versão + Atualizador README
 * Usage: node scripts/release-notes.cjs "<tag>" "<prev_tag>"
 */
const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

const [, , tag, prevTag] = process.argv;
if (!tag) { console.error('Usage: release-notes.cjs <tag> <prev_tag>'); process.exit(1); }

const from = prevTag || 'HEAD~5';
const to = tag || 'HEAD';
let changelog = '';
try {
  changelog = execSync(`git log --pretty=format:"- %s" ${from}..${to} --reverse`, { encoding: 'utf-8' });
} catch (_) {
  changelog = '- (nenhuma mudança detectada via git log)';
}

const notes = `# Notas da Versão — ${tag}\n\nChangelog automático (gerado pelas ações do GitHub):\n\n${changelog.trim()}\n`;
console.log(`=== Release notes for ${tag} ===`);
console.log(notes.trim());

const readmePath = path.join(process.cwd(), 'README.md');
const readme = fs.readFileSync(readmePath, 'utf-8');
const marker = '<!-- release-notes-start -->';
const endMarker = '<!-- release-notes-end -->';
const section = `${marker}\n${notes}\n${endMarker}`;

if (readme.includes(marker)) {
  fs.writeFileSync(readmePath, readme.replace(new RegExp(`${marker}[\s\S]*?${endMarker}`), section));
  console.log('README.md updated.');
} else {
  fs.writeFileSync(readmePath, readme + `\n\n## 🔄 Release Notes (auto)\n\n${section}\n`);
  console.log('Release notes section added ao README.md.');
}
