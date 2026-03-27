const puppeteer = require('puppeteer');

(async () => {
  const [, , url, output] = process.argv;

  if (!url || !output) {
    console.error('Usage: node generate-pdf.js <url> <output>');
    process.exit(1);
  }

  const browser = await puppeteer.launch({
    headless: true,
    args: ['--no-sandbox', '--disable-setuid-sandbox']
  });

  try {
    const page = await browser.newPage();
    await page.goto(url, { waitUntil: 'networkidle0', timeout: 60000 });

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
