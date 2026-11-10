# Frontend Testing Guide — Noosphere Healing Chamber

Manual/regression test checklist for the frontend, organized by user role.
Base URL (dev): `https://noospherehealingchamberdev.exponentialhealthcare.com`

> **Scope note:** The quiz/assessment feature (questions, elements, bodyparts, results, answers, MCQ answers) and the Interpret/Elements/Seasons Nova tools have been **removed** from the project. Their tables were dropped via `2026_11_10_000000_drop_assessment_tables`. Tests below verify the session-based features that remain.

## User Roles

The app uses `users.type_id` to determine role and what the user sees after login:

| type_id | Role | Nova access | Sidebar menu |
|---------|------|-------------|--------------|
| 1 | Client (created by a Practitioner) | ❌ Blocked — logged out with "Access denied" | — |
| 2 | Practitioner | ✅ | Dashboard, Resources → Clients |
| 3 | Admin | ✅ | Dashboard, Resources → Users (all users) |
| 4 | Project Noosphere Premium Member | ✅ | Dashboard, Resources → Start New Session / Resume Sessions / History |
| 5 | Project Noosphere Free Member | ✅ | Same menu as Premium |

## Test Accounts Needed

Create one account per role (Admin → Users → Create User, "Type Id" field):

- [ ] Admin account (type_id = 3)
- [ ] Practitioner account (type_id = 2)
- [ ] Premium Member account (type_id = 4)
- [ ] Free Member account (type_id = 5)
- [ ] Client account assigned to the Practitioner (type_id = 1, practitioner_id = practitioner's ID)

---

## 1. Guest / Authentication

| # | Test | Steps | Expected |
|---|------|-------|----------|
| A1 | Unauthenticated redirect | Open `/` in incognito | Redirected to `/login` |
| A2 | Login page | Open `/login` (or `/nova/login`) | Login form renders, no console errors |
| A3 | Invalid login | Submit wrong password | Error message shown, stays on login |
| A4 | Valid login | Log in with each role (2,3,4,5) | Redirected to `/nova` dashboard |
| A5 | Forgot password | `/forgot-password` → submit email | Reset email sent (check mail log) |
| A6 | Reset password | Use link from email → set new password | Password updated, can log in |
| A7 | Register | `/register` → fill form → submit | Account created, redirected to `/nova/resources/users` |
| A8 | Logout | User menu → Logout | Logged out, `/nova` redirects to login |
| A9 | Blocked role login | Log in as type_id=1 Client | Redirected to login with "Access denied. Only authorized users can access Nova." |
| A10 | Password-protected page | `/password-protected` → submit | Correct password grants access; wrong one rejected |

---

## 2. Admin (type_id = 3)

Sidebar should show: **Dashboard**, **Resources → Users** only.

| # | Test | Steps | Expected |
|---|------|-------|----------|
| AD1 | Dashboard loads | Open `/nova` | Main dashboard cards render, no errors |
| AD2 | Users index | Resources → Users | **All** users listed, labeled "Users" |
| AD3 | Create user — each type | Users → Create User → Type Id 1–5 | Correct conditional fields per type (Password for 1,2,4,5); saves OK |
| AD4 | Conditional fields | Toggle Type Id between 2, 3, others | Dependent fields appear/disappear correctly (Company Name, Country, etc.) |
| AD5 | Auto password | Create user leaving Password empty | User created; random password generated; welcome email sent |
| AD6 | Edit user | User → Edit → change → Save | Changes persist |
| AD7 | User detail | Open a user record | Fields render; "Client Session" relation shown; `/start-session/{id}` URL link present |
| AD8 | Sessions resources | `/nova/resources/user-sessions`, `/nova/resources/end-sessions` | Admin sees **all** sessions |
| AD9 | Other resources | Browse Invitations, Profiles, User Sessions, End Sessions | All index/detail pages render without errors |
| AD10 | Removed tools absent | Check sidebar + visit `/nova/interpret`, `/nova/elements`, `/nova/seasons` | No links in sidebar; URLs return 404 |
| AD11 | Quiz feature absent | Try `/nova/resources/questions`, `/nova/resources/results`, `/nova/resources/answers` | 404 — resources gone |
| AD12 | Replicate removed | Any user detail page | No "Replicate" button |

---

## 3. Practitioner (type_id = 2)

| # | Test | Steps | Expected |
|---|------|-------|----------|
| PR1 | Dashboard | Login → `/nova` | Dashboard loads |
| PR2 | Clients list | Resources → **Clients** (labeled "Clients") | Only own clients (`practitioner_id` = self); other practitioners' clients NOT visible |
| PR3 | Create client | Clients → Create → Type Id = Client(1) | Saved; auto-assigned to logged-in practitioner |
| PR4 | Client profile fields | Create/edit client | Fields: First Name, Last Name, Email, DOB, Place of Birth, Religion (optional), Occupation, Country, City |
| PR5 | Client sessions | `/nova/resources/user-sessions` | Only sessions of own clients shown |
| PR6 | Start session | Client detail → session URL (`/start-session/{client_id}`) | Session page opens for that client |
| PR7 | Send invitation | `/send-invitation/{client_id}` → enter email → Send | Invitation form renders; `InvitationEmail` sent; success message |
| PR8 | Invitation registration | Open `/register-user/{id}` link from email as guest | Registration form scoped to practitioner; new user linked to that practitioner |
| PR9 | Cross-tenant check | Manually open a URL/ID belonging to another practitioner's client | Blocked / not visible — no data leak |
| PR10 | Removed tools | `/nova/interpret`, `/nova/elements`, `/nova/seasons` | 404 |

---

## 4. Members — Premium (4) & Free (5)

Sidebar: **Dashboard**, **Resources → Start New Session / Resume Sessions / History**.
Run all tests for both type 4 and type 5 accounts.

| # | Test | Steps | Expected |
|---|------|-------|----------|
| M1 | Menu | Login → check sidebar | Three links present; no "Clients"/"Users" section |
| M2 | Start New Session | Click "Start New Session" | Opens `/start-session/{own_id}`; new `user_sessions` row created (state=1) |
| M3 | Resume Sessions | Click "Resume Sessions" | Only own **incomplete** sessions (`is_complete=0`); no other users' data |
| M4 | Resume flow | Open session → "Resume Session" | Back to `/start-session/{id}/{session_id}`; photos, gender, recording, type restored |
| M5 | History | Click "History" (`resources/end-sessions`) | Only own completed sessions |
| M6 | Session detail | Open a past session | Images viewable, recording downloadable/playable, total session time shown |
| M7 | Removed tools | `/nova/interpret` etc. | 404 |

---

## 5. Healing Session Flow (end-to-end)

| # | Test | Steps | Expected |
|---|------|-------|----------|
| S1 | Start session | `/start-session/{user_id}` (logged in) | Session page renders; `user_sessions` row created with `session_start`, `state=1` |
| S2 | Upload photos | Upload image_1 / image_2 | Saved to storage; shown on resume |
| S3 | Gender + type | Select gender and healing/session type | Saved to session record |
| S4 | Recording | Upload audio recording | `recording_url` saved; playable later |
| S5 | Notes | Enter session notes (if field present) | Saved with session |
| S6 | End (incomplete) | `/end-session/{user_id}/{session_id}` | `session_end` set; `is_complete=0`; redirect to Nova session edit page |
| S7 | End (complete) | `/end-session/{user_id}/{session_id}/1` | `is_complete=1`; leaves Resume list, appears in History |
| S8 | Resume ended | Re-open `/start-session/{id}/{ended_id}` | Data restores; existing `session_end` preserved |
| S9 | Invalid session | `/end-session/{user_id}/99999` | 404 |
| S10 | Session detail page | Post-session: notes + recording link saved | Saved to session; visible in history detail |

---

## 6. Invitations

| # | Test | Steps | Expected |
|---|------|-------|----------|
| I1 | Invitation record | Practitioner/admin creates invitation | Row in `invitations` table; `InvitationEmail` auto-sent to invitee (observer) |
| I2 | Register via link | `/register-user/{id}` → register | User created linked to inviting practitioner |
| I3 | Nova resource | Admin → Invitations resource | Lists invitations; detail renders |
| I4 | Invitation email | Check inbox/mail log | Correct invitee name + practitioner name + register link |

---

## 7. Account Pages (all Nova roles)

| # | Test | Steps | Expected |
|---|------|-------|----------|
| U1 | My Profile | User menu → My Profile (`/nova/profile`) | Profile page with Nova sidebar loads |
| U2 | Update profile | Change fields → Save | Persists; success feedback |
| U3 | Change Password | User menu → Change Password (`/nova/change-password`) | Submit new password → can log in with it |
| U4 | Wrong current password | Submit wrong current password | Validation error; unchanged |
| U5 | Notifications page | User menu → Notifications (`/nova/notifications`) | ⚠️ **Known issue on dev:** `NotificationsController` class is missing — page may 500. Document behavior |
| U6 | Legacy routes | `/change-password`, `/nova/profile` direct GET | Still work |

---

## 8. API / Session Endpoints

| # | Test | Expected |
|---|------|----------|
| API1 | `POST /api/startSession` valid payload | Session created/updated; JSON response |
| API2 | `POST /api/upload` with file + session | File stored; attached to session |
| API3 | `POST /api/create-profile` | Profile saved (first_name…city fields) |
| API4 | Invalid/missing params on each | Validation errors, no 500 |

---

## 9. Security / Access Control

| # | Test | Expected |
|---|------|----------|
| SEC1 | Client (type_id 1) hits `/nova` | Logged out → login "Access denied" |
| SEC2 | Guest hits `/nova/*` | Redirect to Nova login |
| SEC3 | Member/practitioner views another's data | Scoped out — no cross-user leakage |
| SEC4 | Direct `/start-session/{other_user_id}` as member | Document behavior (currently no check — flag) |
| SEC5 | Type_id 4/5 access to Users resource | Blocked/scoped — members shouldn't see user management |

---

## 10. Cross-cutting

- [ ] No JS console errors on any page (DevTools open throughout)
- [ ] Footer: "Powered by Exponential Healthcare © {year}"
- [ ] `/up` health endpoint returns OK
- [ ] Mobile/tablet responsiveness spot-check (session page especially)
- [ ] DB state matches UI after each session action
- [ ] Dropped tables verified gone: `elements`, `bodyparts`, `questions`, `results`, `answers`, `mcq_answers` no longer in DB

## Notes for the tester

- Nova pages are Vue/Inertia-rendered inside `/nova/*`; wait for full render before asserting.
- Custom Nova pages (Profile, Change Password, Notifications) load via `public/js/nova-pages.js` — a 404 on that asset means `npm run build-nova-pages` needs re-running.
- Session pages are plain Blade outside the Nova shell.
- Check `storage/logs/laravel.log` for email issues; mail uses the `MAIL_*` settings in `.env`.
