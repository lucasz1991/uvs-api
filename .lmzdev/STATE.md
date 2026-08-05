# Current state

## Confirmed

- LMZ Dev workspace initialized.
- `POST /api/participants/store` requires `hubspot_deal_id` according to the HubSpot-Make procedure dated 2026-07-27.
- Existing-person imports are idempotent for an already known Deal-ID and reject conflicting or cross-person Deal-IDs with HTTP 409.
- The active offer flow consumes a pending person mapping based on prior concrete `x_hubspot` offer mappings, not merely historical local offer rows, and fails closed on multiple/stale pending rows.
- The complete participant Deal-ID decision logic now lives directly in `ParticipantApiController::store()`; no task-specific helper or test file remains.
- The E: API checkout and authoritative XAMPP API checkout contain byte-identical `ParticipantApiController.php` files.
- The unpacked deployment folder `Dev/HubSpot-Make-Verfahren/UVS_HubSpot_Make_Update_2026-07-27` and its adjacent ZIP were refreshed in place from the confirmed current sources.
- The consolidated package has 13 files: all previous PDF/document/config changes, the corrected offer flow, the inline `ParticipantApiController.php`, the existing `secrets.php` `.gitignore` protection, and Revision 3 documentation. No task helper/test file is included.
- A 15-page external handoff document now exists at `Dev/HubSpot-Make-Verfahren/UVS_HubSpot_API-Dokumentation_2026-08-04.pdf`. It documents participant import, offer/contract synchronization through Make, signed PDF retrieval, examples, errors, retry/idempotency behavior, and a go-live checklist without embedding secrets or production URLs.
- A separate six-page reduced handoff document now exists at `Dev/HubSpot-Make-Verfahren/UVS_API_Personen-und-Dateien_2026-08-04.pdf`. Its scope is limited to `POST /api/participants/store` and retrieval of a UVS-provided signed PDF URL; it excludes webhook and offer/contract synchronization documentation.
- `config/uvs.php` no longer reads `UVS_ROOT`. It derives the physical `uvs_dev` sibling directory from the Laravel `uvs_api` base path, which resolves an IIS deployment such as `C:\inetpub\wwwroot\uvs_api` to `C:\inetpub\wwwroot\uvs_dev`. Allowed document subdirectories remain `data/pdf/angebote` and `data/pdf/vertraege`.
- The E: checkout, authoritative XAMPP mirror, unpacked deployment folder, ENV examples, `LIESMICH.txt`, `UVS/AiSync.txt`, and adjacent deployment ZIP were updated consistently. The real `.env` remains untouched and no longer needs a document path entry.
- `public/web.config` now exists in the E: checkout, XAMPP mirror, and deployment folder. It keeps existing files/directories untouched and rewrites other IIS requests to Laravel `index.php`; the deployment documentation states that IIS URL Rewrite and an application mapping to `uvs_api/public` are prerequisites.

## Verification

- Before cleanup, the focused decision test passed 10 tests / 10 assertions; the temporary test was then deleted as requested.
- After cleanup, `php artisan test --testsuite=Unit --no-ansi` -> 6 remaining tests, 18 assertions passed.
- `php -l` passed for both controller copies and `../../UVS/php/db_query/1_4_2_save_angebot.php`.
- Validation matrix for `required|string|max:64`: missing/null/empty/JSON number/65 chars rejected; numeric string accepted.
- SHA-256 comparison confirmed both API checkout copies are identical for the controller.
- `git diff --check` passed in both repositories.
- All 11 package files that directly mirror repository sources are SHA-256 identical; the two documented API environment keys also match the `.env.example` source.
- Every one of the 13 ZIP file entries matches the unpacked folder byte-for-byte; the ZIP has the expected wrapper folder and no temporary build artifacts.
- Final ZIP: 44,392 bytes, SHA-256 `F47C4D24E838344CFF9CF2203937FA99A32D9FD0EBC87ADC0B5B0BAFA4DB0D25`.
- The external API PDF passed full visual inspection of all 15 pages, text extraction checks for the documented interfaces, and a PDF character-bounds scan with zero out-of-page characters. The delivery and internal artifact copies are byte-identical; SHA-256 `2A7BD12D77BD10CB7968C85E5B79BFFFE29CDC85F1FA5812884A713F9D2872FC`.
- The reduced six-page API PDF passed visual inspection of every page, required/forbidden scope term checks, and a character-bounds scan with zero out-of-page characters. Delivery and internal artifact copies are byte-identical; SHA-256 `A3ACDE6F95442E70167B6BE0C06E2AE516A2BD0B03A8AD19A68F3AA231A558A9`.
- PHP lint passed for all three `config/uvs.php` copies; they are byte-identical with SHA-256 `AD8CE679C8A1C8D425B3FB7110BB1D95BB60BF658BED9D8AE67E1B2AC39A06C7`. Both main `.env.example` files are byte-identical and no active/config/deployment template contains `UVS_ROOT`.
- API Unit suite passed 6 tests / 18 assertions; both document routes are registered; `git diff --check` passed apart from pre-existing line-ending warnings.
- Refreshed deployment ZIP contains 12 source-matching files under the expected wrapper, verified byte-for-byte; 37,859 bytes, SHA-256 `C9A8D6DF8E360BB01D06C611D16B7DB4E9345CF73E7567B878A1D3FDBE8D02A4`.
- All three IIS `public/web.config` copies are valid XML and byte-identical, SHA-256 `28D77E235EA6E5EA227F5B8CFAA32B39285A26A1D1C671CF82059D2B0E337330`. Unit suite remains 6 tests / 18 assertions and both document routes remain registered.
- The deployment ZIP now contains 13 source-matching files including `uvs-api/public/web.config`, verified byte-for-byte; 38,465 bytes, SHA-256 `0467EE58078D8CFCE7E26BEFB66EF83EEA1FCB10C940FA5A18217702298308A5`.
- `documents.sign` is now offered in the existing API-key ability multi-select; selected values continue to be stored in `settings.abilities` and displayed generically by `UserApiKeysPanel`.
- Verification passed for PHP lint, the registered `documents.sign` route, modal rendering, ability enforcement, `git diff --check`, and the Unit suite (6 tests / 18 assertions).

## Risks and blockers

- The locally configured UVS database has no `x_hubspot` table; a real participant-store integration test therefore cannot run and would return 500 until the target schema is deployed/selected.
- Make/HubSpot end-to-end behavior is unverified; local external-send configuration is disabled.
- The ordinary/EC contract send flow still needs an explicit contract UID/type and persisted `angebot_id`; guessing the latest contract/offer would risk routing a PDF to the wrong Deal-ID.
- Signed-document item binding, API SQL exposure, direct legacy endpoint access, and direct `data/pdf` access are separate pre-go-live security tasks.
- The supplied IIS root `web.config` only adds `index.php` as a default document. IIS Manager must still map `uvs_api` as an application to its `public` directory, provide Laravel URL rewriting, grant the API application-pool identity read access to the two `uvs_dev/data/pdf` directories, and set the correct HTTPS `APP_URL` before signed URLs can be proven end-to-end.
- Public `phpinfo.php` exposes server/runtime details and should be removed or blocked after IIS diagnosis; it was not deleted because the user only asked for analysis/configuration of the document path.
- FileZilla is connected as `lmz@192.168.1.134`, but Computer Use could not capture its visual state (`Schnittstelle nicht unterstuetzt`, `0x80004002`) and FileZilla exposed no usable accessibility tree. No blind upload or remote overwrite was performed; `public/web.config` remains pending for remote upload/verification.
