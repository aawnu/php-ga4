# Project Summary

## Objective
Implemented the `todo.md` GA4 backlog in this repository without executing runtime tracking flows.

## What was implemented

### 1) Event payload correctness and completeness
- Fixed event serialization so `session_id` and `engagement_time_msec` are now included in event `params`.
- Added event-level timestamp override support (`event.timestamp_micros`) via `setEventTimestampMicros(...)`.
- Added campaign parameter setters and merge support in event helpers:
  - `campaign_source`, `campaign_medium`, `campaign_name`, `campaign_term`, `campaign_content`, `campaign_id`.

### 2) Top-level Measurement Protocol request fields
- Added support for top-level request fields in both Analytics and Firebase payload models:
  - `non_personalized_ads`
  - `validation_behavior`
  - `ip_override`
  - `user_location`
  - `device`
- Added public setters for the above in both models.

### 3) User properties and user data
- Added user property timestamp support via `setTimestampMicros(...)`, serialized as `set_timestamp_micros`.
- Removed hard gate that only allowed `user_data` when `user_id` was present; payload now includes `user_data` when provided.
- Improved phone normalization API to accept `int|string` and sanitize formatted inputs to E.164-compatible hashing format.

### 4) Cookie/session integration helpers
- Improved session cookie parsing (`parseSessionCookie`) for GS1/GS2 formats:
  - fixed `timestamp` key typo
  - normalized GS2 values by removing token prefixes
  - added version/domain metadata consistently
- Added client ID extraction helpers:
  - `parseClientIdFromGaCookie(...)`
  - `parseClientIdFromCookies(...)`
  - `parseGaCookies(...)`

### 5) Required-parameter audit updates
- Tightened required params for selected events based on GA4 recommended semantics used in backlog:
  - `EarnVirtualCurrency`: requires `virtual_currency_name`, `value`
  - `JoinGroup`: requires `group_id`
  - `LevelUp`: requires `level`
  - `Search`: requires `search_term`

### 6) Tests and backlog tracking
- Updated and extended tests for:
  - event timestamp override
  - campaign serialization
  - top-level request fields
  - user property timestamp
  - cookie parsing normalization and client ID helpers
  - formatted phone normalization
- Marked `todo.md` tasks complete.

## Files added
- `agent.md`

## Notable files updated
- `src/Helper/EventMainHelper.php`
- `src/Helper/EventHelper.php`
- `src/Helper/EventParamsHelper.php`
- `src/Analytics.php`
- `src/Firebase.php`
- `src/Helper/UserPropertyHelper.php`
- `src/UserProperty.php`
- `src/Helper/UserDataHelper.php`
- `src/Helper/ConvertHelper.php`
- `src/Event/EarnVirtualCurrency.php`
- `src/Event/JoinGroup.php`
- `src/Event/LevelUp.php`
- `src/Event/Search.php`
- `src/Facade/Type/AnalyticsType.php`
- `src/Facade/Type/FirebaseType.php`
- `src/Facade/Type/DefaultEventParamsType.php`
- `src/Facade/Type/UserPropertyType.php`
- `test/Unit/EventTest.php`
- `test/Unit/AnalyticsTest.php`
- `test/Unit/FirebaseTest.php`
- `test/Unit/HelperTest.php`
- `test/Unit/UserDataTest.php`
- `test/Unit/UserPropertyTest.php`
- `todo.md`

## Validation status
- Runtime test/lint execution was not completed because `php` is unavailable in this environment (`php: command not found`).
