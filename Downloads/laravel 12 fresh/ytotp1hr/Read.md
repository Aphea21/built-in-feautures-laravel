Great question 👍
At this point, you’re **not asking for code anymore**, you’re asking:

> “How do I manually test this OTP-based email authentication (Register + Forgot Password) like a real tester so I can be confident it works and doesn’t regress?”

Below is a **complete, structured MANUAL TESTING GUIDE** you can literally follow step-by-step. This is how QA testers actually test features like this.

---

# ✅ MANUAL TESTING GUIDE

**Email Authentication (Register + OTP + Forgot Password)**

This is written so you can test **without guessing**, **without touching code**, and **without breaking flows**.

---

## 🧩 PRE-TEST CHECKLIST (DO THIS FIRST)

Before testing, make sure:

1. **Database**

   * `users` table has these columns:

     * `otp`
     * `otp_expires_at`
     * `last_otp_sent_at`
     * `otp_attempts`
     * `email_verified_at`
   * Clear test users if needed:

     ```sql
     DELETE FROM users WHERE email LIKE '%test%';
     ```

2. **Email**

   * You already confirmed Brevo works ✅
   * Use a **real Gmail inbox** you can refresh

3. **Browser**

   * Use **Incognito / Private Window**
   * This avoids session bugs during testing

---

# 🧪 TEST SUITE 1 — REGISTRATION + OTP

---

## TC-REG-01: Successful Registration + OTP Verification

**Steps**

1. Go to `/register`
2. Enter:

   * Name: `Test User`
   * Email: `test1@gmail.com`
   * Password: `password123`
3. Submit

**Expected**

* Redirected to **Verify OTP (registration)** page
* OTP email arrives within 5–10 seconds
* Database:

  * `otp` → NOT NULL
  * `otp_expires_at` → future time
  * `email_verified_at` → NULL

---

## TC-REG-02: Wrong OTP

**Steps**

1. Enter random OTP (e.g. `111111`)
2. Submit

**Expected**

* Error: **“The provided OTP is incorrect.”**
* Database:

  * `otp_attempts` increments by 1
* User NOT logged in

---

## TC-REG-03: OTP Attempts Limit

**Steps**

1. Enter wrong OTP repeatedly (e.g. 5 times)

**Expected**

* Error like:

  > “Too many OTP attempts. Please request a new OTP.”
* OTP form should stop accepting attempts
* (Optional) OTP invalidated

---

## TC-REG-04: OTP Expiration

**Steps**

1. Wait until `otp_expires_at` passes (or manually edit DB)
2. Enter correct OTP

**Expected**

* Error: **“OTP has expired.”**
* User NOT verified
* Prompt to resend OTP

---

## TC-REG-05: Resend OTP Cooldown

**Steps**

1. Click **Resend OTP**
2. Immediately click again (within 60 seconds)

**Expected**

* Error:

  > “Please wait X seconds before requesting a new OTP.”
* No new email sent
* `last_otp_sent_at` unchanged

---

## TC-REG-06: Successful OTP Verification

**Steps**

1. Enter correct OTP before expiration

**Expected**

* Redirect to `/home`
* Database:

  * `email_verified_at` → timestamp
  * `otp` → NULL
  * `otp_expires_at` → NULL
  * `otp_attempts` → reset
* User logged in

---

# 🧪 TEST SUITE 2 — LOGIN PROTECTION

---

## TC-LOGIN-01: Login without OTP Verification

**Steps**

1. Register a new user
2. DO NOT verify OTP
3. Try to log in

**Expected**

* Redirected to OTP verification
* Message:

  > “Please verify your email first.”

---

## TC-LOGIN-02: Login After Verification

**Steps**

1. Verify OTP
2. Log out
3. Log in again

**Expected**

* Login successful
* Redirect to `/home`

---

# 🧪 TEST SUITE 3 — FORGOT PASSWORD FLOW

---

## TC-FP-01: Forgot Password Email OTP

**Steps**

1. Go to `/forgot-password`
2. Enter registered email
3. Submit

**Expected**

* Redirect to **Forgot Password OTP Verify page**
* OTP email sent
* Database:

  * `otp` updated
  * `otp_expires_at` updated
  * `last_otp_sent_at` updated

---

## TC-FP-02: Wrong OTP (Forgot Password)

**Steps**

1. Enter wrong OTP

**Expected**

* Error message
* `otp_attempts` increments

---

## TC-FP-03: OTP Expired (Forgot Password)

**Steps**

1. Wait past expiration
2. Enter correct OTP

**Expected**

* Error: OTP expired
* Prompt to resend

---

## TC-FP-04: Resend OTP Cooldown (Forgot Password)

**Steps**

1. Click Resend OTP
2. Click again within cooldown

**Expected**

* Cooldown message
* No new email

---

## TC-FP-05: Successful OTP → Reset Password

**Steps**

1. Enter correct OTP
2. Redirected to **Reset Password form**
3. Enter:

   * New password
   * Confirm password

**Expected**

* Password updated in DB (hashed)
* `otp` cleared
* `otp_attempts` reset
* Redirect to login

---

## TC-FP-06: Login with New Password

**Steps**

1. Login using new password

**Expected**

* Login success
* Old password no longer works

---

# 🧪 TEST SUITE 4 — EDGE & SECURITY CASES

---

## TC-SEC-01: Reusing OTP

**Steps**

1. Use OTP successfully
2. Try using same OTP again

**Expected**

* OTP rejected
* User redirected properly

---

## TC-SEC-02: Resend OTP After Verification

**Steps**

1. Verify email
2. Try resend OTP endpoint

**Expected**

* Blocked or ignored
* No new OTP generated

---

## TC-SEC-03: Session Expired

**Steps**

1. Request OTP
2. Clear cookies
3. Open OTP page

**Expected**

* Redirect back to forgot/register
* Error like:

  > “Session expired. Please try again.”

---

# 📝 WHAT TO RECORD (AS A TESTER)

For each test case, note:

* ✅ Pass / ❌ Fail
* Error message accuracy
* DB state changes
* Email delay time
* UX confusion points

This is **panel-ready** documentation.

---

## 🎯 FINAL VERDICT

✔ Your architecture is **correct**
✔ Separate OTP flows = GOOD design
✔ Cooldowns + attempts = production-level
✔ This is **capstone-quality** email authentication

If you want next:

* A **formal test case table (Excel/Docs)**
* Or a **QA checklist for panel defense**
* Or **security hardening suggestions**

Just tell me what you want next.
