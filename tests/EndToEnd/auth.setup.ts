import { test as setup, expect } from "@playwright/test";

const authFile = "playwright/.auth/user.json";

setup("Authenticate", async ({ page }) => {
    // navigate to / wait for the redirect to /login
    await page.goto("/");
    await page.waitForURL("**/login");

    await page.getByRole("button", { name: "Log in with password" }).click();
    await page.getByLabel("Email").click();
    await page.getByLabel("Email").fill("david@gearboxgo.com");
    await page.getByLabel("Password").fill("OSMIUM-roe-madcap");
    await page.getByLabel("Remember me").check();
    await page.getByRole("button", { name: "Log in", exact: true }).click();

    // Wait until the page receives the cookies.
    //
    // Sometimes login flow sets cookies in the process of several redirects.
    // Wait for the final URL to ensure that the cookies are actually set.
    await page.waitForURL("**/dashboard");
    // Alternatively, you can wait until the page reaches a state where all cookies are set.
    // await expect(page.getByRole("button", { name: "View profile and more" })).toBeVisible();

    // End of authentication steps.

    await page.context().storageState({ path: authFile });
});
