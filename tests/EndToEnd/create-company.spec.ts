import { test, expect } from "@playwright/test";

test("Create and Delete Company", async ({ page }) => {
    await page.goto("dashboard");
    await page.getByRole("link", { name: "Companies" }).click();
    await page.getByRole("link", { name: "Create New" }).click();
    await page.getByLabel("Name").fill("Gearbox");
    await page.getByLabel("Name").press("Tab");
    await page.getByLabel("Website").fill("https://gearboxgo.com");
    await page.getByLabel("Website").press("Tab");
    await page.getByLabel("Phone Number").fill("770-765-6258");
    await page.getByLabel("Phone Number").press("Tab");
    await page.getByLabel("Street").fill("34 Park Ln NE");
    await page.getByLabel("Street").press("Tab");
    await page.getByLabel("City").fill("Atlanta");
    await page.getByLabel("City").press("Tab");
    await page.locator("#address_state svg").click();
    await page.getByText("Georgia").click();
    await page.getByLabel("Zip").click();
    await page.getByLabel("Zip").fill("30309");

    // start listening for the response to the post request
    const responsePromise = page.waitForResponse((resp) => resp.url().includes("/companies") && resp.ok());

    // submit the form
    await page.getByRole("button", { name: "Save" }).click();

    // check and make sure we had a successful response
    const response = await responsePromise;
    await expect(response.ok()).toBeTruthy();

    // assert that we're not still on the create page
    await expect(!page.url().includes("create")).toBeFalsy();
    const pageUrl = /.*\/companies\/\d+/;
    await page.waitForURL(pageUrl);

    const companyId = page.url().split("/")[4];

    // delete the company we just created
    await page.goto(`companies/${companyId}`);
    await page.getByRole("button", { name: "Delete" }).click();
    await page.waitForURL("**/companies");
});
