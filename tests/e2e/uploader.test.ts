import { test, expect } from '@playwright/test';

const IMAGE_PATH = process.env.IMAGE_PATH

test.describe('Uploader Page', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('/uploader');
  });

  test('should display uploader form', async ({ page }) => {
    await expect(page.locator('form')).toBeVisible();
    await expect(page.locator('input[type="file"]')).toBeVisible();
    await expect(page.locator('input[name="watermark"]')).toBeVisible();
    await expect(page.locator('input[name="author"]')).toBeVisible();
    await expect(page.locator('input[name="title"]')).toBeVisible();
    await expect(page.locator('button[type="submit"]')).toBeVisible();
  });

  test('should show error message for missing file', async ({ page }) => {
    await page.fill('input[name="watermark"]', 'Test Watermark');
    await page.fill('input[name="author"]', 'Test Author');
    await page.fill('input[name="title"]', 'Test Title');
    await page.click('button[type="submit"]');
    await expect(page.locator('.message-box--error')).toBeVisible();
  });

  test('should upload image successfully', async ({ page }) => {
    const filePath = IMAGE_PATH;
    await page.setInputFiles('input[type="file"]', filePath);
    await page.fill('input[name="watermark"]', 'Test Watermark');
    await page.fill('input[name="author"]', 'Test Author');
    await page.fill('input[name="title"]', 'Test Title');
    await page.click('button[type="submit"]');
    await expect(page).toHaveURL('/gallery');
  });
});
