# Project Overview — Autopahala

## Project Vision

**Autopahala** is a crowdfunding platform built with Laravel that enables users to create fundraising campaigns, receive donations through Midtrans payment gateway, and manage fund withdrawals. The platform aims to provide a transparent, secure, and user-friendly environment for charitable giving in Indonesia.

## Problem Being Solved

Traditional charitable giving in Indonesia often lacks transparency and accessibility. Autopahala addresses this by:

- Providing a digital platform where anyone can create verified fundraising campaigns
- Enabling secure online payments through Midtrans (bank transfer, e-wallet, credit card)
- Implementing admin verification to prevent fraud
- Offering real-time progress tracking for donors
- Supporting social features (follow, like, comment) to build community trust

## Core Features

| Feature | Status | Description |
|---------|--------|-------------|
| Campaign Creation | ✅ Implemented | Users create campaigns with title, description, target amount, media |
| Campaign Verification | ✅ Implemented | Admin reviews and approves/rejects campaigns via Filament |
| Donation System | ✅ Partial | Donation model and migration exist; payment flow scaffolded |
| Midtrans Payment | ✅ Partial | MidtransService with Snap integration; webhook controller scaffolded |
| User Profiles | ✅ Implemented | Public profiles with bio, social links, cover photo |
| OAuth Login | ✅ Implemented | Google and GitHub via Laravel Socialite |
| Admin Panel | ✅ Implemented | Filament 4 with full CRUD for campaigns, users, donations, withdrawals |
| Campaign Updates | 🔲 Planned | Not yet implemented |
| Follow User | 🔲 Planned | Not yet implemented |
| Like Campaign | 🔲 Planned | Not yet implemented |
| Comment System | 🔲 Planned | Not yet implemented |
| Report Campaign | 🔲 Planned | Not yet implemented |
| Leaderboard | 🔲 Planned | Not yet implemented |
| Notifications | 🔲 Planned | Not yet implemented |

## User Roles

### 1. Guest (Unauthenticated)
- Browse homepage and public campaigns
- View public user profiles (`/@username`)
- Access static pages (about, contact, FAQ)

### 2. User (Authenticated, `role = 'user'`)
- Create fundraising campaigns
- Edit/delete own campaigns (when in draft/pending status)
- Make donations to campaigns
- Manage personal profile (bio, avatar, cover photo, social links)
- Access user dashboard at `/dashboard`

### 3. Admin (`role = 'admin'`)
- Access Filament admin panel at `/admin`
- Approve/reject campaigns
- Manage all users (CRUD, suspend, verify)
- View and manage all donations
- Process withdrawal requests
- View admin activity logs

## Technology Stack

| Layer | Technology | Version |
|-------|-----------|---------|
| Framework | Laravel | 13.x |
| PHP | PHP | 8.3+ |
| Admin Panel | Filament | 4.11 |
| Frontend | Livewire | 3.6 |
| CSS | Tailwind CSS | (via Vite) |
| Auth | Laravel Jetstream + Fortify | 5.5 |
| OAuth | Laravel Socialite | 5.27 |
| Payment | Midtrans PHP SDK | 2.6 |
| Database | MySQL | — |
| API Tokens | Laravel Sanctum | 4.0 |
| Testing | PHPUnit | 12.5 |

## System Boundaries

```
┌─────────────────────────────────────────────────────────┐
│                    AUTOPAHALA SYSTEM                      │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  ┌──────────────┐    ┌──────────────┐    ┌───────────┐  │
│  │ Public Site  │    │  User Panel  │    │Admin Panel│  │
│  │  (Blade +   │    │ (Dashboard)  │    │(Filament) │  │
│  │  Tailwind)  │    │              │    │           │  │
│  └──────┬───────┘    └──────┬───────┘    └─────┬─────┘  │
│         │                   │                  │         │
│  ┌──────┴───────────────────┴──────────────────┴─────┐  │
│  │              Laravel Application Layer             │  │
│  │  Controllers → Services → Models → Database       │  │
│  └───────────────────────┬───────────────────────────┘  │
│                          │                               │
├──────────────────────────┼───────────────────────────────┤
│  External Services       │                               │
│  ┌───────────────┐  ┌────┴──────┐  ┌─────────────────┐  │
│  │   Midtrans    │  │   MySQL   │  │  Google/GitHub   │  │
│  │  (Payments)   │  │    DB     │  │    (OAuth)       │  │
│  └───────────────┘  └───────────┘  └─────────────────┘  │
└─────────────────────────────────────────────────────────┘
```

## Project Name Origin

"Autopahala" combines "Auto" (automatic) + "Pahala" (Indonesian for "merit/reward"), suggesting that doing good deeds (donating) earns spiritual merit automatically through the platform.
