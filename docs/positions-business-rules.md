# Business Rules: Position Tracking and Average Cost Basis

This document outlines the core business rules governing the behavior of security positions and how they are updated when trades occur. These rules apply to both LONG and SHORT positions, with a focus here on LONG positions using the **Average Cost Basis** method.

---

## Core Concepts

- **Position**: A record representing holdings of a specific security (LONG or SHORT).
- **Total Quantity**: The number of units currently held.
- **Total Cost**: The accumulated cost basis of the currently held units.
- **Average Cost per Unit**: `total_cost / quantity`
- **Cost Basis of Sale**: The cost basis assigned to the number of units sold.
- **Realized Gain**: The difference between sale proceeds and the cost basis of the units sold.

---

## Business Rules for LONG Positions

### 🔹 Opening or Increasing a Position (Buy to Open)

- A Buy to Open trade **creates** a new LONG position if none exists, or **increases** an existing one.
- The trade’s quantity is **added** to the position’s total quantity.
- The trade’s cost (including fees) is **added** to the position’s total cost.
- The average cost per unit is recalculated as:

      average_cost = total_cost / quantity

- The `last_traded_at` timestamp is updated.
- If this is the first trade, `position_opened_at` is also set.

---

### 🔹 Reducing or Closing a Position (Sell to Close)

- A Sell to Close trade **must** apply to an existing LONG position.
- The quantity sold is **subtracted** from the position’s total quantity.
- The **cost basis of the sold shares** is calculated using the current average cost:

      cost_basis_of_sale = average_cost * quantity_sold

- This cost basis is **subtracted** from the total cost of the position.
- The realized gain is calculated as:

      realized_gain = proceeds - cost_basis_of_sale

- The `last_traded_at` timestamp is updated.
- The average cost per share remains **unchanged** by the sale.

---

### 🔹 Position Lifecycle

- If a position’s quantity becomes zero after a trade, it is considered **closed**, but historical data is retained.
- A new position in the same security and type may later be opened with a new `position_opened_at`.

---

## Additional Notes

- **You can hold both a LONG and a SHORT position** in the same security; they are tracked separately.
- The tuple `(security_id, type)` forms the **natural key** for uniquely identifying a position.
- Fees are assumed to be included in total cost calculations.
- Proceeds from sales **do not** affect the position’s total cost basis — only realized gains.
