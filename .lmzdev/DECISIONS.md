# Decisions

Record durable decisions with date, context, decision, and consequences.

## 2026-08-04 - Incoming first HubSpot Deal-ID is mandatory and single-owner

- Context: The procedure states that a person originates in HubSpot and the first existing Deal-ID is always transferred with that person.
- Decision: Validate `hubspot_deal_id` as required. Treat the same Deal-ID anywhere on the same person as an idempotent retry; reject a new unknown Deal-ID for an already mapped person and reject a Deal-ID owned by another person.
- Consequence: Missing IDs can no longer create silent second deals, and retries cannot recreate a stale pending person mapping.

## 2026-08-04 - Pending mappings are consumed by mapping state, not raw offer history

- Context: Existing persons can have historical local offers without any HubSpot mapping.
- Decision: The first-offer path checks for prior concrete `x_hubspot` offer mappings. A single pending person mapping may bootstrap the next unmapped offer; multiple or stale pending mappings fail closed.
- Consequence: Legacy unmapped offers no longer strand the valid initial HubSpot Deal-ID.

## 2026-08-04 - Do not guess contract-to-offer association

- Context: Normal and EC contract flows do not reliably post/persist an immutable contract UID and `angebot_id`.
- Decision: Leave those flows fail closed until the UI/data path carries an explicit contract type, contract UID, and offer association.
- Consequence: No heuristic "latest contract/offer" fix can silently update the wrong HubSpot deal.

## 2026-08-04 - Keep participant import logic in the existing controller

- Context: The user requested cleanup and no task-specific helper/test files for this narrowly scoped store-flow change.
- Decision: Keep the verified Deal-ID ownership, retry, and conflict logic inline in `ParticipantApiController::store()` and remove the temporary decision class/test after verification.
- Consequence: The functional behavior remains unchanged while the production change is contained in the existing controller file.

## 2026-08-04 - Refresh the existing deployment artifacts as one consolidated package

- Context: The user explicitly requested renewal of the existing update ZIP and the unpacked folder beside it while preserving all other adjustments.
- Decision: Keep the established `UVS_HubSpot_Make_Update_2026-07-27` artifact names, retain the full existing package scope, add the corrected participant controller and offer mapping logic, include the existing secrets `.gitignore` protection, and document the August change as Revision 3.
- Consequence: The folder and ZIP are direct equivalents with 13 files; no parallel partial hotfix, helper, test, credential, `.env`, or `.lmzdev` content is shipped.

## 2026-08-04 - External API handoff documents only partner-facing interfaces

- Context: The receiving company needs a usable contract for the two integration functions without disclosure of internal signing endpoints, credentials, or production infrastructure.
- Decision: Document `POST /api/participants/store` and the UVS-to-Make offer/contract webhook as the two main functions. Document the signed `pdf_url` only as the externally consumed opaque GET link; keep the internal `/api/documents/sign` operation outside the partner contract.
- Consequence: The handoff is implementation-ready while secrets, internal signing mechanics, and production URLs remain separate deployment data.

## 2026-08-04 - Provide a separate two-function API document

- Context: The user clarified that the additional handoff must contain only person storage and file retrieval, with no webhook or synchronization documentation.
- Decision: Create a separate compact PDF limited to `POST /api/participants/store` and `GET /api/documents/{typ}/pdf` through the complete signed URL supplied by UVS. Keep `/api/documents/sign` internal and omit the Make webhook entirely.
- Consequence: The original comprehensive PDF remains unchanged, while the new six-page PDF can be handed over when only these two interfaces are in scope.

## 2026-08-04 - Derive the fixed UVS document root from the IIS sibling layout

- Context: The confirmed server layout has `/uvs_api/.env` and `/uvs_dev/data/pdf/...` as sibling installations in the IIS site root. A PHP string `/uvs_dev` is a URL-style path and is not a reliable Windows filesystem path.
- Decision: Remove the `UVS_ROOT` ENV contract and derive the physical root as `dirname(base_path()) . DIRECTORY_SEPARATOR . 'uvs_dev'`. Keep the two allowed relative directories explicit in `config/uvs.php`.
- Consequence: No drive-specific or secret path value is required, while deployments keep the fixed `uvs_api`/`uvs_dev` structure. IIS application mapping and filesystem permissions remain server-level prerequisites rather than guessed root-web.config changes.

## 2026-08-04 - Ship Laravel's IIS rewrite locally but do not perform a blind FileZilla upload

- Context: The root `web.config` does not route Laravel requests and the API checkout had no `public/web.config`. FileZilla was connected, but Computer Use could neither capture the window nor expose actionable accessibility elements.
- Decision: Add a distributed IIS rewrite rule in `uvs_api/public/web.config`, mirror and package it, but stop before remote upload when the target directory and overwrite state cannot be observed.
- Consequence: The deployment file is ready and verified without risking an upload to the wrong remote directory. IIS application mapping, module installation, permissions, and the final remote verification still require a functioning UI or server administration access.

## 2026-08-05 - Use the exact document signing route name as API-key ability

- Context: `ApiKeyMiddleware` authorizes requests against the current Laravel route name.
- Decision: Add `documents.sign` to the existing `ApiKeyFormModal` ability options; do not add a separate panel mapping because `UserApiKeysPanel` already displays all stored abilities generically.
- Consequence: Administrators can grant only URL-signing access without granting `all`, and the stored value matches the middleware check exactly.
