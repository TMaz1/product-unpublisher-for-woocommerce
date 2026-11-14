// End-to-end tests against a real WordPress + WooCommerce started by wp-env (see .wp-env.json).
const { defineConfig } = require('@playwright/test');

module.exports = defineConfig({
	testDir: './tests/e2e',
	testMatch: /wp-.*\.spec\.js/,
	timeout: 120000,
	workers: 1,
	reporter: [['list']],
	use: {
		baseURL: process.env.WP_BASE_URL || 'http://localhost:8889',
		headless: true,
		navigationTimeout: 60000,
		trace: 'retain-on-failure',
	},
});