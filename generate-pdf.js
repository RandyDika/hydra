const puppeteer = require('puppeteer');
const fs = require('fs');

(async () => {
  const [, , input, output] = process.argv;

  if (!input || !output) {
    console.error('Usage: node generate-pdf.js <html-file> <output>');
    process.exit(1);
  }

  const html = fs.readFileSync(input, 'utf-8');

  const browser = await puppeteer.launch({
    executablePath: process.env.PUPPETEER_EXECUTABLE_PATH || undefined,
    headless: true,
    args: ['--no-sandbox', '--disable-setuid-sandbox']
  });

  try {
    const page = await browser.newPage();

    await page.setContent(html, { waitUntil: 'networkidle0' });

    await page.pdf({
      path: output,
      format: 'A4',
      printBackground: true,
      preferCSSPageSize: true,
      margin: {
        top: '0mm',
        right: '0mm',
        bottom: '0mm',
        left: '0mm'
      }
    });
  } finally {
    await browser.close();
  }
})();