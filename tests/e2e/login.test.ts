import { test, expect } from '@playwright/test';

test.describe('Login Page', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('/login');
  });

  test('should display login form', async ({ page }) => {
    await expect(page.locator('form')).toBeVisible();
    await expect(page.locator('input[name="username"]')).toBeVisible();
    await expect(page.locator('input[name="password"]')).toBeVisible();
    await expect(page.locator('button[type="submit"]')).toBeVisible();
  });

  test('should show error message for invalid credentials', async ({ page }) => {
    await page.fill('input[name="username"]', 'invaliduser');
    await page.fill('input[name="password"]', 'invalidpassword');
    await page.click('button[type="submit"]');
    await expect(page.locator('.message-box--error')).toBeVisible();
  });

  test('should redirect to home page for valid credentials', async ({ page }) => {
    await page.fill('input[name="username"]', 'validuser');
    await page.fill('input[name="password"]', 'validpassword');
    await page.click('button[type="submit"]');
    await expect(page).toHaveURL('/');
  });
});
