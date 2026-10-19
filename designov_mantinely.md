# AI Design Guidelines: Vodácká 30

**Context:** You are an expert UI/UX engineer and frontend developer. You are building a web application for "Vodácká 30" – a traditional winter cross-country skiing race in the Czech mountains. The primary users are outdoor athletes, organizers, and timekeepers. 

**Tech Stack:** Laravel 11+, Blade Components, Tailwind CSS, minimal Alpine.js.

## 1. Aesthetic & Vibe
*   **Theme:** "Winter Outdoor Utility". The design must feel cold, crisp, and professional. 
*   **Inspiration:** Think of professional sports data apps like Strava, Garmin Connect, or Salomon's e-commerce. 
*   **Goal:** High data density (for race results), extreme readability, and functional minimalism. 

## 2. The "Anti-Slop" Strict Rules
DO NOT use generic AI design trends. Strictly adhere to the following prohibitions:
*   **NO huge, blurry shadows:** Do not use `shadow-xl`, `shadow-2xl`, or colored shadows (e.g., `shadow-indigo-500/50`). Use `shadow-sm` or just a 1px border.
*   **NO extreme border radii:** Do not use `rounded-2xl`, `rounded-3xl`, or `rounded-full` for containers. Cards and inputs should be `rounded-md` or `rounded-lg` maximum. 
*   **NO gradients:** Avoid `bg-gradient-to-r`. Use solid colors. 
*   **NO low-contrast text:** Never use light gray text on a white background for important data.
*   **NO custom CSS classes:** Strictly use Tailwind utility classes. Do not invent classes like `.btn-primary` or `.card`.

## 3. Color Palette
Stick strictly to this defined palette. Do not improvise colors.
*   **Backgrounds:** 
    *   Page body: `bg-slate-50` (A cold, snowy off-white to reduce eye strain).
    *   Cards/Containers: `bg-white`.
*   **Typography:**
    *   Headings & Primary Data: `text-slate-900`.
    *   Body text & Secondary Data: `text-slate-600`.
    *   Muted/Meta text (dates, small labels): `text-slate-500`.
*   **Primary Accent (High-visibility "Rescue" Orange):**
    *   Buttons/Active states: `bg-orange-600`.
    *   Hover states: `hover:bg-orange-700`.
    *   Focus rings: `focus:ring-orange-600`.
*   **Borders & Dividers:**
    *   `border-slate-200` for subtle separation.

## 4. Typography & Spacing
*   **Font:** Use the default Tailwind sans-serif font stack.
*   **Spacing System:** Strictly adhere to the 8pt grid (`4`, `8`, `12`, `16`, `24`, `32`). Do not use arbitrary values like `-[17px]`.
*   **Whitespace:** Use generous vertical padding between major page sections (e.g., `py-16` or `py-24`).

## 5. Key Component Patterns

### Cards (Propositions, News, Layout blocks)
Use crisp borders and very subtle shadows. 
*   *Base classes:* `bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden`

### Buttons
Keep them sharp and solid.
*   *Primary:* `inline-flex justify-center items-center rounded-md bg-orange-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-orange-700 transition-colors`
*   *Secondary:* `inline-flex justify-center items-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-colors`

### Data Tables (Race Results, Start Lists)
Tables must be extremely readable on both mobile and desktop.
*   *Headers:* `bg-slate-50 text-xs font-medium text-slate-500 uppercase tracking-wider border-b border-slate-200`
*   *Rows:* `border-b border-slate-200 hover:bg-slate-50 transition-colors`
*   *Alignment:* Left-align names and clubs. Right-align numbers (times, start numbers, placements).

### Forms (Registration, Contact)
Clean, standard browser-like inputs with proper focus states.
*   *Inputs:* `block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-orange-600 sm:text-sm sm:leading-6`
*   *Labels:* `block text-sm font-medium leading-6 text-slate-900`

## 6. Implementation Notes for Laravel Blade
*   Always extract repeating UI elements into Blade Components (e.g., `<x-button>`, `<x-card>`, `<x-form.input>`).
*   Ensure all forms account for Laravel's `@csrf` and display `@error` validation messages using a small, red text class (`text-red-600 text-sm mt-1`).