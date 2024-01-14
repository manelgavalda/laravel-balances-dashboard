import { it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import Dashboard from '../Pages/Dashboard.vue'

const dashboard = mount(Dashboard, {
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
          price: 9,
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
      {
        'Token 1': {
          pool: 'Token 1',
          balance: 7,
          price: 8,
          price_eur: 9,
          created_at: 'ayer'
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
}).vm

it("returns_daily_change", async () => {
  expect(dashboard.getDailyChange('Token 1')).toEqual("11.11")
});

it("returns_weekly_apy", async () => {
  expect(dashboard.getWeeklyApy('Token 1')).toEqual("30.00")
});

it("returns_weekly_gain", async () => {
  expect(dashboard.getWeeklyGain('Token 1')).toEqual("30.00")
});

it("returns_monthly_apy", async () => {
  expect(dashboard.getMonthlyApy('Token 1')).toEqual("30.00")
});

it("returns_monthly_gain", async () => {
  expect(dashboard.getMonthlyGain('Token 1')).toEqual("30.00")
});

it("returns_balance_history", async () => {
  expect(dashboard.getBalanceHistory('Token 1')).toEqual([7, 0, 0, 7, 5, 9, 10])
});

it("returns_price_history", async () => {
  expect(dashboard.getPriceHistory('Token 1')).toEqual([56, 0, 0, 70, 50, 81, 100])
});

it("formats_the_number_as_a_currency", async () => {
  expect(dashboard.currencyFormat(32121.123)).toBe("32,121.123");
});
