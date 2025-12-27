# Architecture Conventions

This document records architectural conventions that guide design decisions
across the codebase. These conventions are intended to preserve domain purity,
clarity of intent, and long-term maintainability.

## Outcome vs Outcomes

This project distinguishes between singular and plural *Outcome* types to
separate domain concerns from application orchestration.

### Outcome (singular)

- Represents a business outcome or decision
- Belongs to the **Domain** layer
- Immutable
- Encodes business meaning and invariants
- Examples:
  - ConfirmationOutcome
    - NewConfirmationCreated
  - PositionOutcome
    - NewPositionCreated
    - IncreasedHolding
    - DecreasedHolding
  - SecurityOutcome
    - NewSecurityCreated
    - NoChange
    - VariationAdded

### Outcomes (plural)

- Represents a composite of multiple domain outcomes
- Belongs to the **Application** layer
- Immutable
- Produced by application processors
- Consumed by outcome engines (e.g. persistence, import, simulation)

Examples:
- TradeProcessingOutcomes

This convention ensures that the domain remains unaware of orchestration,
persistence, and simulation concerns, while enabling flexible application-level
pipelines.
