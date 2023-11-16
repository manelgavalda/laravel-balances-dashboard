import { it, expect } from 'vitest'
import Dashboard from '../Pages/Dashboard.vue'

it("testing currencyFormat", async () => {
  const currencyFormat = (number) => Dashboard.methods.currencyFormat(number)

  expect(currencyFormat(32121.123)).toBe("32,121.123");
});
