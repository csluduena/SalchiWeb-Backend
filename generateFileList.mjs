import fs from 'fs';
import path from 'path';

// Lista de extensiones de archivos multimedia a excluir
const mediaExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.bmp', '.webp', '.svg', '.mp3', '.wav', '.ogg', '.mp4', '.avi', '.mov', '.mkv', '.flv', '.ico', '.webm'];

// Función para recorrer el directorio de manera recursiva
function getFilesRecursively(dir, excludedDirs, excludedFiles, fileList = []) {
  const files = fs.readdirSync(dir);  // Lee el contenido del directorio

  files.forEach(file => {
    const filePath = path.join(dir, file); // Genera la ruta completa del archivo
    const stats = fs.statSync(filePath);  // Obtiene la información del archivo

    // Si es un directorio, lo recorre
    if (stats.isDirectory()) {
      if (!excludedDirs.includes(file)) {
        getFilesRecursively(filePath, excludedDirs, excludedFiles, fileList); // Recursión
      }
    } else {
      // Si el archivo no está en la lista de excluidos y no es un archivo multimedia
      const fileExt = path.extname(file).toLowerCase(); // Obtener la extensión del archivo

      if (!excludedFiles.includes(file) && !mediaExtensions.includes(fileExt)) {
        fileList.push(filePath);
      }
    }
  });

  return fileList;
}

// Función para leer el contenido de un archivo
function getFileContent(filePath) {
  return fs.readFileSync(filePath, 'utf-8');
}

// Función principal para generar el archivo de salida
function generateFileList(outputFile) {
  const excludedDirs = ['node_modules', 'vendor', 'dist', '.github', '.git'];  // Carpetas a excluir
  const excludedFiles = ['package-lock.json', 'yarn.lock', 'README.md']; // Archivos a excluir
  const projectDir = process.cwd();  // Directorio actual
  const files = getFilesRecursively(projectDir, excludedDirs, excludedFiles);

  let outputData = '';

  // Recopilación de datos y contenido de archivos
  files.forEach(filePath => {
    const fileContent = getFileContent(filePath);
    outputData += `Archivo: ${filePath}\nCódigo:\n${fileContent}\n\n`;
  });

  // Escribir el archivo de salida
  fs.writeFileSync(outputFile, outputData);
  console.log(`Se generó el archivo: ${outputFile}`);
}

// Llamada para generar el archivo de salida
generateFileList('output.txt');
