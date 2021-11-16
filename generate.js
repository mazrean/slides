const pug = require('pug');
const fs = require('fs');

const compiledFunction = pug.compileFile('index.pug');

fs.readdir("./", function (err, files) {
  if (err) throw err;

  const slides = files.filter(function (file) {
    return fs.statSync(file).isDirectory() && file !== 'node_modules' && !/^\..*/.test(file);
  })

  const html = compiledFunction({
    slides: slides,
  })

  fs.writeFile("index.html", html, err => {
    if (err) throw err;
  });
})
