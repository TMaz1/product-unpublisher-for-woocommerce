const { execSync } = require('child_process');

/** Run a WP-CLI command inside the wp-env tests container and return trimmed stdout. */
const { execFileSync } = require('child_process');
const wpEnvBin = require.resolve('@wordpress/env/bin/wp-env');

function wp(args) {
	const argList = Array.isArray(args)
		? args
		: args.match(/(?:[^\s"']+|"[^"]*"|'[^']*')+/g).map((s) => s.replace(/^['"]|['"]$/g, ''));

		// works cross - platform with zero shell escaping issues
	return execFileSync(process.execPath, [wpEnvBin, 'run', 'tests-cli', 'wp', ...argList], {
		encoding: 'utf8',
		stdio: ['ignore', 'pipe', 'pipe'],
	})
		.split('\n')
		.filter((line) => line && !line.startsWith('ℹ') && !line.startsWith('✔'))
		.join('\n')
		.trim();
}

function createProduct(title, status = 'publish') {
	return Number(wp(`post create --post_type=product --post_status=${status} --post_title="${title}" --porcelain`));
}

function status(id) {
	return wp(`post get ${id} --field=post_status`);
}

function scheduledMeta(id) {
	try {
		return wp(`post meta get ${id} _wps_unpublish_time`);
	} catch (error) {
		return ''; // WP-CLI exits non-zero when the meta key does not exist.
	}
}

// function cronEvents() {
// 	const raw = wp('cron event list --fields=hook,args --format=json');
// 	const start = raw.indexOf('[');
// 	if (start === -1) {
// 		return [];
// 	}
// 	return JSON.parse(raw.slice(start)).filter(
// 		(event) => event.hook === 'wpu_unpublish_product'
// 	);
// }

function cronEvents() {
	const raw = wp('option get cron --format=json');
	const start = raw.indexOf('{');
	if (start === -1) {
		return [];
	}

	const cron = JSON.parse(raw.slice(start));
	const events = [];

	for (const [timestamp, hooks] of Object.entries(cron)) {
		if (typeof hooks !== 'object' || hooks === null || !hooks.wpu_unpublish_product) {
			continue;
		}
		for (const entry of Object.values(hooks.wpu_unpublish_product)) {
			events.push({
				hook: 'wpu_unpublish_product',
				timestamp: Number(timestamp),
				args: entry.args || [],
			});
		}
	}

	return events;
}

// async function login(page) {
// 	const cookieName = process.env.WP_AUTH_COOKIE_NAME;
// 	const cookieValue = process.env.WP_AUTH_COOKIE;

// 	if (!cookieName || !cookieValue) {
// 		throw new Error('Missing WordPress authentication cookie. Generate it before running tests.');
// 	}

// 	await page.context().addCookies([
// 		{
// 			name: cookieName,
// 			value: cookieValue,
// 			domain: 'localhost',
// 			path: '/',
// 			httpOnly: true,
// 			secure: false,
// 			sameSite: 'Lax',
// 		},
// 	]);

// 	await page.goto('/wp-admin/', { waitUntil: 'domcontentloaded', timeout: 60000 });
// 	await page.waitForURL('**/wp-admin/**', { timeout: 15000 });
// }

async function login(page) {
	await page.goto('/wp-login.php', { waitUntil: 'domcontentloaded' });
	await page.fill('#user_login', 'admin');
	await page.fill('#user_pass', 'password');
	await Promise.all([
		page.waitForURL('**/wp-admin/**', { waitUntil: 'domcontentloaded' }),
		page.click('#wp-submit'),
	]);
}

module.exports = { wp, createProduct, status, scheduledMeta, cronEvents, login };
