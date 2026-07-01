// @ts-check
import { test, expect } from '@playwright/test';

test.describe('Page CV publique', () => {
    test('affiche la page publique avec un statut 200', async ({ page }) => {
        const response = await page.goto('/');
        expect(response?.status()).toBe(200);
    });

    test('contient la balise html et body', async ({ page }) => {
        await page.goto('/');
        await expect(page.locator('body')).toBeVisible();
    });
});

test.describe('Back-office — authentification', () => {
    test('redirige vers le login si non connecté', async ({ page }) => {
        await page.goto('/admin');
        await expect(page).toHaveURL(/\/admin\/login/);
    });

    test('affiche le formulaire de connexion', async ({ page }) => {
        await page.goto('/admin/login');
        await expect(page.locator('input[type="email"]')).toBeVisible();
        await expect(page.locator('input[type="password"]')).toBeVisible();
    });
});
