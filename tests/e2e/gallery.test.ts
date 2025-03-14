import { test, expect } from '@playwright/test';

test.describe('Gallery Page', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('/gallery');
  });

  test('should display gallery items', async ({ page }) => {
    const galleryItems = await page.$$('.gallery-item');
    expect(galleryItems.length).toBeGreaterThan(0);
  });

  test('should search for gallery items', async ({ page }) => {
    await page.fill('input[name="search"]', 'test');
    await page.press('input[name="search"]', 'Enter');
    const galleryItems = await page.$$('.gallery-item');
    expect(galleryItems.length).toBeGreaterThan(0);
  });

  test('should save gallery item', async ({ page }) => {
    const firstGalleryItem = await page.$('.gallery-item');
    await firstGalleryItem.click();
    const saveButton = await page.$('button:has-text("Remember selected items")');
    await saveButton.click();
    const savedItems = await page.$$('.gallery-item[saved]');
    expect(savedItems.length).toBeGreaterThan(0);
  });

  test('should forget saved gallery item', async ({ page }) => {
    const savedItems = await page.$$('.gallery-item[saved]');
    const firstSavedItem = savedItems[0];
    await firstSavedItem.click();
    const forgetButton = await page.$('button:has-text("Forget selected items")');
    await forgetButton.click();
    const updatedSavedItems = await page.$$('.gallery-item[saved]');
    expect(updatedSavedItems.length).toBeLessThan(savedItems.length);
  });
});
