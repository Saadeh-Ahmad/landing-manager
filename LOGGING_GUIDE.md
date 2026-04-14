# Comprehensive Logging Guide

## Overview

The system now has detailed logging at every step of the subscription flow, from OTP/PIN request to portal redirect.

## Log Locations

**Main Log File:**
```bash
/var/www/html/Quick-Fun/storage/logs/laravel.log
```

## Viewing Logs

### Real-time Monitoring

```bash
# Watch all logs in real-time
tail -f storage/logs/laravel.log

# Filter specific flow
tail -f storage/logs/laravel.log | grep "=========="
```

### Filter by Component

```bash
# DCB Flow only
tail -f storage/logs/laravel.log | grep "DCB"

# OTP Flow only
tail -f storage/logs/laravel.log | grep "OTP"

# Session ID only
tail -f storage/logs/laravel.log | grep "SESSION"

# Portal redirect only
tail -f storage/logs/laravel.log | grep "PORTAL"

# Billing API only
tail -f storage/logs/laravel.log | grep "BILLING"
```

## Log Structure

### 1. DCB Send Pincode

```
========== DCB SEND PINCODE REQUEST ==========
- timestamp: 2025-11-10 10:00:00
- service: dcb_mediaworld
- msisdn: 9647801234567
- api_url: https://services.mediaworldiq.com:456/sendPinCode?...
- ip_address: 109.107.243.35
- user_agent: Mozilla/5.0...

========== DCB SEND PINCODE RESPONSE ==========
- timestamp: 2025-11-10 10:00:01
- status_code: 200
- success: true
- response_body: {"status":"Success"...}
- response_time_ms: 523
```

### 2. DCB Verify Pincode

```
========== DCB VERIFY PINCODE REQUEST ==========
- timestamp: 2025-11-10 10:01:00
- service: dcb_mediaworld
- msisdn: 9647801234567
- pincode: ****
- api_url: https://services.mediaworldiq.com:456/verifyPinCode?...
- ip_address: 109.107.243.35
- user_agent: Mozilla/5.0...

========== DCB VERIFY PINCODE RESPONSE ==========
- timestamp: 2025-11-10 10:01:01
- status_code: 200
- success: true
- response_body: {"status":"Success","msg":"Subscription Activated"...}
```

### 3. Session ID Request

```
========== REQUESTING SESSION ID ==========
- timestamp: 2025-11-10 10:01:02
- msisdn: 9647801234567
- service: dcb_mediaworld

---------- BILLING API: Session ID Request ----------
- timestamp: 2025-11-10 10:01:02
- billing_url: https://billing.quickfun.games/mediaworld/token
- msisdn: 9647801234567
- service_id: 7
- expires_at: 2025-11-11 10:01:02
- jwt_token_length: 808

---------- BILLING API: Session ID Response ----------
- timestamp: 2025-11-10 10:01:03
- status_code: 200
- success: true
- response_time_ms: 234.56
- response_body: {"sid":"abc123xyz..."}

========== SESSION ID RESPONSE ==========
- timestamp: 2025-11-10 10:01:03
- success: true
- session_id: abc123xyz...
- portal_url: https://billing.quickfun.games/mediaworld/gateway/abc123xyz.../7
- error: null
```

### 4. Portal Redirect

```
========== PORTAL REDIRECT PREPARED ==========
- timestamp: 2025-11-10 10:01:03
- msisdn: 9647801234567
- redirect_url: https://billing.quickfun.games/mediaworld/gateway/abc123xyz.../7
- session_id: abc123xyz...
```

### 5. OTP Flow (Alternative)

```
========== OTP REQUEST ==========
- timestamp: 2025-11-10 10:00:00
- msisdn: 9647901234567
- campaign: campaign1
- ip_address: 109.107.243.35

========== OTP VERIFY REQUEST ==========
- timestamp: 2025-11-10 10:01:00
- msisdn: 9647901234567
- otp: ****
- ip_address: 109.107.243.35

========== OTP VERIFY RESPONSE ==========
- timestamp: 2025-11-10 10:01:00
- verified: true
- message: OTP verified successfully

========== OTP FLOW: Requesting Session ID ==========
- timestamp: 2025-11-10 10:01:01
- msisdn: 9647901234567
- subscriber_id: 123

========== OTP FLOW: Session ID Result ==========
- timestamp: 2025-11-10 10:01:02
- success: true
- session_id: def456uvw...
- portal_url: https://billing.quickfun.games/mediaworld/gateway/def456uvw.../7
```

## Quick Commands

### Check Last 100 Lines
```bash
tail -100 storage/logs/laravel.log
```

### Search for Specific MSISDN
```bash
grep "9647801234567" storage/logs/laravel.log
```

### Count Successful Verifications Today
```bash
grep "$(date +%Y-%m-%d)" storage/logs/laravel.log | grep "VERIFY.*SUCCESS" | wc -l
```

### Find Failed Sessions
```bash
grep "SESSION ID RESPONSE" storage/logs/laravel.log | grep "success.*false"
```

### Export Today's Logs
```bash
grep "$(date +%Y-%m-%d)" storage/logs/laravel.log > today-logs.txt
```

### Monitor Session ID Failures
```bash
tail -f storage/logs/laravel.log | grep -E "SESSION.*false|error"
```

## Log Levels

- **INFO**: Normal flow (all logs above)
- **ERROR**: Exceptions and failures
- **WARNING**: Issues that don't stop the flow

## Troubleshooting

### Issue: No Session ID

**Check:**
```bash
tail -f storage/logs/laravel.log | grep -A 5 "BILLING API"
```

Look for:
- HTTP status codes (should be 200)
- Response body (should contain "sid")
- Error messages

### Issue: Portal Redirect Not Working

**Check:**
```bash
tail -f storage/logs/laravel.log | grep -A 3 "PORTAL REDIRECT"
```

Verify:
- Portal URL is generated
- Session ID is present
- URL format is correct

### Issue: OTP Not Sending

**Check:**
```bash
tail -f storage/logs/laravel.log | grep -A 5 "OTP REQUEST"
```

### Issue: PIN Verification Failing

**Check:**
```bash
tail -f storage/logs/laravel.log | grep -B 5 -A 5 "VERIFY PINCODE"
```

## Log Rotation

Laravel automatically rotates logs daily. Old logs are saved as:
```
laravel-YYYY-MM-DD.log
```

## Production Recommendations

1. **Monitor disk space:**
```bash
du -h storage/logs/
```

2. **Archive old logs:**
```bash
tar -czf logs-backup-$(date +%Y%m%d).tar.gz storage/logs/*.log
```

3. **Clear old logs:**
```bash
# Keep only last 7 days
find storage/logs/ -name "laravel-*.log" -mtime +7 -delete
```

## Summary

✅ **Logged Events:**
- DCB Send Pincode (Request + Response)
- DCB Verify Pincode (Request + Response)
- OTP Request
- OTP Verification
- Session ID Request (Request + Response)
- Session ID Response
- Portal Redirect Preparation
- Timing information
- Error tracking

All logs include timestamps, MSISDN, and relevant context for debugging!

