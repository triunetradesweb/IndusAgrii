# 🌾 Indus Agrii

Indus Agrii is a modern, scalable, and responsive agricultural e-commerce platform focused on providing high-quality food grains, millets, rice, and agri-based products with a seamless user experience across all devices.

The project is built with performance, accessibility, SEO, and maintainability as core principles.

---

## 📌 Project Objectives

- Build a clean and intuitive agricultural e-commerce platform
- Ensure fast loading, smooth UI, and mobile-first responsiveness
- Maintain strict UI consistency using Tailwind CSS only
- Support scalable backend logic with secure session handling
- Deliver a professional-grade user and admin experience

---

## 🧩 Core Features

- User authentication (login / register / session handling)
- Product listing with categories (Rice, Millets, etc.)
- Wishlist functionality (AJAX-based)
- Cart and reorder functionality
- Order history and invoice generation
- Responsive header with mobile navigation
- Smooth animations and transitions
- SEO-friendly structure and semantic HTML
- Secure database interactions using prepared statements

---

## 🛠 Tech Stack

### Frontend
- HTML5
- Tailwind CSS (utility-only, no custom CSS files)
- Vanilla JavaScript (optimized, modular)

### Backend
- PHP (procedural + structured logic)
- MySQL (via prepared statements)

### Other
- AJAX (for wishlist, reorder, cart actions)
- Session-based authentication
- Responsive design principles

---

## 🎨 UI & Design Principles

- Tailwind CSS only (no inline styles, no separate CSS files)
- Clean and readable class naming
- Large, accessible typography
- Butter-smooth animations
- No layout shift or page jank
- Fully responsive across:
  - Mobile
  - Tablets
  - Laptops
  - Large monitors
  - TVs

---


## 🧠 Application Architecture
IndusAgrii/
│
├── config/
│ └── database.php
│
├── public/
│ ├── index.php
│ ├── assets/
│ └── uploads/
│
├── includes/
│ ├── header.php
│ ├── footer.php
│ └── auth.php
│
├── pages/
│ ├── rice.php
│ ├── millets.php
│ ├── wishlist.php
│ └── orders.php
│
├── actions/
│ ├── wishlist.php
│ ├── reorder.php
│ └── cart.php
│
├── js/
│ └── main.js
│
└── README.md


---

## 🔐 Authentication & Sessions

- PHP sessions are used for login state
- Unauthorized users are redirected safely
- Session variables are sanitized and validated
- Logout destroys all session data securely

---

## 🛒 Cart & Reorder Flow

- Products can be reordered from order history
- Reorder button is disabled during processing
- Loading spinner prevents multiple submissions
- Toast notifications provide instant feedback
- Cart state updates dynamically without page reload

---

## ❤️ Wishlist System

- AJAX-based toggle (add/remove)
- Works from any page
- Session-aware and user-specific
- Real-time UI update without reload

---

## 📦 Orders & Invoices

- Order history is fetched securely
- Invoice generator matches predefined Excel layout
- Print-ready A4 formatting
- Consistent spacing and typography

---

## 📱 Responsive Behavior

- Mobile-first layout
- Header adapts for mobile navigation
- No blur or backdrop issues on overlay menus
- Touch-friendly interactions
- Optimized for low-end devices

---

## ⚡ Performance Optimizations

- Minimal JavaScript execution
- No unnecessary reflows or repaints
- Tailwind JIT optimized classes
- Deferred scripts where applicable
- Lightweight DOM operations

---

## 🔍 SEO & Accessibility

- Semantic HTML structure
- Proper heading hierarchy
- Meta tags for all major pages
- Accessible contrast ratios
- Keyboard navigation support

---

## 🧪 Browser Compatibility

Tested and supported on:
- Chrome
- Firefox
- Edge
- Safari
- Mobile browsers (Android & iOS)

---

## 🚀 Deployment Notes

- PHP 8+ recommended
- MySQL 5.7+
- Apache / Nginx supported
- Ensure writable permissions for uploads
- Update database credentials in `config/database.php`

---

## 📄 License

This project is proprietary and intended for internal or client use.
Unauthorized distribution or reuse is not permitted.

---

## 🤝 Contribution Guidelines

- Follow existing coding standards
- Do not introduce custom CSS
- Maintain UI and UX consistency
- Test across devices before committing
- Keep commits clean and descriptive

---

## ✅ Project Status

🟢 Actively developed  
🟢 Production-ready architecture  
🟢 Scalable and maintainable codebase

---

**Indus Agrii** – Building a reliable digital bridge between agriculture and consumers 🌱

