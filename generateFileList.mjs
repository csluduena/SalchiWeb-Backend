import fs from 'fs';
import path from 'path';

// Lista de archivos específicos que se deben incluir
const includedFiles = [
  'database.php',
  'authcontroller.php',
  'user.php',
  'jwthandler.php',
  'router.php',
  'server.php'
];

// Función para obtener los archivos que coinciden con la lista específica
function getSpecificFiles(dir, includedFiles, fileList = []) {
  const files = fs.readdirSync(dir);  // Lee el contenido del directorio

  files.forEach(file => {
    const filePath = path.join(dir, file); // Genera la ruta completa del archivo
    const stats = fs.statSync(filePath);  // Obtiene la información del archivo

    // Si es un directorio, lo recorre
    if (stats.isDirectory()) {
      getSpecificFiles(filePath, includedFiles, fileList); // Recursión
    } else {
      // Si el archivo está en la lista de archivos específicos, lo agrega a la lista
      if (includedFiles.includes(file)) {
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
  const projectDir = process.cwd();  // Directorio actual
  const files = getSpecificFiles(projectDir, includedFiles);

  let outputData = '';

  // Recopilación de datos y contenido de los archivos específicos
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
