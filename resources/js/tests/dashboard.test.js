import { it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import Dashboard from '../Pages/Dashboard.vue'

const wrapper = mount(Dashboard, {
  propsData: {
    tokens: [
      {
        'Token 1': {
          pool: 'Token 1',
          balance: 10,
          price: 10,
          price_eur: 9,
          created_at: 'ayer'
        }
      },
      {
        'Token 1': {
          pool: 'Token 1',
          balance: 9,
          price: 10,
          price_eur: 9,
          created_at: 'ayer'
        }
      },
      {
        'Token 1': {
          pool: 'Token 1',
          balance: 5,
          price: 10,
          price_eur: 9,
          created_at: 'ayer'
        }
      },
      {
        'Token 1': {
          pool: 'Token 1',
          balance: 7,
          price: 10,
          price_eur: 9,
          created_at: 'ayer'
        }
      },
      {},
      {},
      {},
    ],
    balances: {
      dates: [1],
      prices: [1],
      totals: [1],
      bitcoin: [1],
      ethereum: [1],
      totals_eur: [1],
      prices_eur: [1],
      btc_prices: [1],
      btc_prices_eur: [1]
    }
  }
})

it("formats_the_number_as_a_currency", async () => {
  expect(wrapper.vm.currencyFormat(32121.123)).toBe("32,121.123");
});

it("returns_historical_balances_properly", async () => {
  expect(wrapper.vm.getBalanceHistory('Token 1')).toStrictEqual([0, 0, 0, 7, 5, 9, 10])
});
