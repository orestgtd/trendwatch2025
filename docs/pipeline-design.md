# Pipeline Design

This document captures the **overarching design principles and decisions** guiding how pipelines are built in this project.  

---

## Guiding Principles

1. **Pipelines as Pure Transformations**  
   Pipelines should focus on *transforming inputs into domain concepts*.  
   They avoid side effects such as database queries or persistence, which are handled externally.

2. **Explicit Side Effects**  
   Reading and writing to external systems (databases, files, APIs) are performed outside the pipeline.  
   The pipeline produces data structures (e.g. a `UnitOfWork`) that *describe* what should happen, leaving execution to the caller.

3. **Testability Through Purity**  
   By keeping pipelines pure, they can be tested in isolation without infrastructure dependencies.  
   Impure steps are isolated, making them easier to mock or stub.

4. **Context Objects Over Lookups**  
   Pipelines receive sufficient context from the caller (e.g. “existing security or draft security”) rather than performing their own lookups.  
   This keeps responsibilities clear: pipelines transform, callers prepare.

5. **Result Transparency**  
   Pipelines always return explicit success/failure results.  
   Errors are part of the return type, not hidden in exceptions, making error handling predictable and composable.

---

## Why This Matters

- **Predictability** — Pure transformations are easier to reason about.  
- **Flexibility** — Different persistence strategies can be plugged in without changing pipelines.  
- **Composability** — Pipelines can be combined or extended without hidden side effects.  
- **Traceability** — By separating “what to do” (pipeline) from “when/how to do it” (execution), the system makes intent clear.

---

## Future Extensions

- The same principles will apply to other domains (e.g. Positions, Orders).  
- This document will grow as we make additional design decisions, serving as a record of architectural intent.
