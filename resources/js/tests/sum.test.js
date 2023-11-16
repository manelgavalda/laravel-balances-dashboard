import { it, expect } from 'vitest'
import Dashboard from '../Pages/Dashboard.vue'

it("testing GuessAge component props", async () => {
  expect(Dashboard.methods.currencyFormat(12132121.123)).toContain("12,132,121.123");
});
