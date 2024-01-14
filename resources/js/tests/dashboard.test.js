import axios from 'axios'
import { it, expect, vi } from 'vitest'
import Dashboard from '../Pages/Dashboard.vue'
import { shallowMount, flushPromises } from '@vue/test-utils'

vi.mock('axios')

const dashboard = shallowMount(Dashboard, {
  propsData: {
    tokens: [
      {
        'Token 1': {
          pool: 'Token 1',
          balance: 10,
          price: 10,
          price_eur: 9,
          created_at: 'now'
        }
      },
      {
        'Token 1': {
          pool: 'Token 1',
          balance: 9,
          price: 9,
          price_eur: 9,
          created_at: 'now'
        }
      },
      {
        'Token 1': {
          pool: 'Token 1',
          balance: 5,
          price: 10,
          price_eur: 9,
          created_at: 'now'
        }
      },
      {
        'Token 1': {
          pool: 'Token 1',
          balance: 7,
          price: 10,
          price_eur: 9,
          created_at: 'now'
        }
      },
      {},
      {},
      {
        'Token 1': {
          pool: 'Token 1',
          balance: 7,
          price: 8,
          price_eur: 9,
          created_at: 'now'
        }
      }
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

const data = {
  "balances": [
    {
      "pool": "Token 1",
      "price": 20,
      "price_eur": 19,
      "balance": 10
    },
  ],
  "bitcoinPrice": {
    "usd": 40000,
    "eur": 39000
  },
  "ethereumPrice": {
    "usd": 2500,
    "eur": 2300
  }
}

it("returns_daily_change", async () => {
  expect(dashboard.vm.getDailyChange('Token 1')).toEqual("11.11")
});

it("returns_weekly_apy", async () => {
  expect(dashboard.vm.getWeeklyApy('Token 1')).toEqual("30.00")
});

it("returns_weekly_gain", async () => {
  expect(dashboard.vm.getWeeklyGain('Token 1')).toEqual("30.00")
});

it("returns_monthly_apy", async () => {
  expect(dashboard.vm.getMonthlyApy('Token 1')).toEqual("30.00")
});

it("returns_monthly_gain", async () => {
  expect(dashboard.vm.getMonthlyGain('Token 1')).toEqual("30.00")
});

it("returns_balance_history", async () => {
  expect(dashboard.vm.getBalanceHistory('Token 1')).toEqual([7, 0, 0, 7, 5, 9, 10])
});

it("returns_price_history", async () => {
  expect(dashboard.vm.getPriceHistory('Token 1')).toEqual([56, 0, 0, 70, 50, 81, 100])
});

it("formats_the_number_as_a_currency", async () => {
  expect(dashboard.vm.currencyFormat(32121.123)).toBe("32,121.123");
});

it('refreshes_the_tokens', async () => {
  axios.get.mockResolvedValue({data})

  expect(dashboard.vm.loading).toEqual(false)

  dashboard.find('button').trigger('click')

  expect(dashboard.vm.loading).toEqual(true)

  await flushPromises()

  expect(dashboard.vm.loading).toEqual(false)
  expect(dashboard.vm.totals.usd).toEqual(200)
})
