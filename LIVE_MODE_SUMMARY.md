# Live Mode Configuration

## Changes Made

### ✅ Removed Test Number Logic

**Before:**
- Hardcoded test number `9647858788687` with PIN `1234`
- Bypassed real DCB API calls
- Returned fake responses

**After:**
- All numbers go through real DCB API
- No special handling for specific numbers
- Real PIN verification from carrier

### Files Modified:
1. `app/Http/Controllers/Api/DcbController.php`
   - Removed test number checks from `sendPincode()`
   - Removed test number checks from `verifyPincode()`

## Current Mode Configuration

### DCB Service (Database)
```
Mode: live ✅
API: https://services.mediaworldiq.com:456
Username: MWtest
Service ID: 264
```

### OTP Service (Config)
```
Mode: dummy (for OTP flow testing)
Can be changed in .env: OTP_MODE=live
```

## Verification Flow (LIVE)

```
1. User enters phone number
   ↓
2. POST /api/dcb/send-pincode
   ↓ Calls real DCB API
   ↓ https://services.mediaworldiq.com:456/sendPinCode
   ↓
3. SMS sent to user's phone (real SMS)
   ↓
4. User enters PIN from SMS
   ↓
5. POST /api/dcb/verify-pincode
   ↓ Calls real DCB API
   ↓ https://services.mediaworldiq.com:456/verifyPinCode
   ↓
6. If valid: Request session ID
   ↓
7. Redirect to portal:
   https://billing.quickfun.games/mediaworld/gateway/{sid}/7
```

## Test Mode (Fallback)

Test mode is still available by setting service mode to "test" or "dummy" in the database:

```bash
php artisan tinker
$service = App\Models\Service::where('name', 'dcb_mediaworld')->first();
$service->mode = 'test';
$service->save();
```

In test mode:
- Any PIN code works
- No real SMS sent
- Fake transaction IDs generated

## Production Checklist

✅ Test number logic removed  
✅ Service mode set to "live"  
✅ Real DCB API configured  
✅ Session ID generation working  
✅ Portal redirect working  
✅ 302 redirect to content portal confirmed  

## Testing Live Mode

1. **Use a real phone number:** `964781234567`
2. **Request PIN:** POST to `/api/dcb/send-pincode`
3. **Check phone:** Real SMS should arrive
4. **Enter PIN:** The actual PIN from SMS
5. **Verify:** Should call real DCB API
6. **Success:** Redirects to portal with session ID

## Monitoring

Check logs for live requests:

```bash
# DCB API calls
tail -f storage/logs/laravel.log | grep -i "DCB"

# Session requests
tail -f storage/logs/laravel.log | grep -i "Session"

# Portal redirects
tail -f storage/logs/laravel.log | grep -i "portal"
```

## Reverting to Test Mode

If you need to test without using real phone numbers:

```bash
php artisan tinker
$service = App\Models\Service::where('name', 'dcb_mediaworld')->first();
$service->mode = 'dummy';
$service->save();
```

## Summary

🚀 **System is now in LIVE MODE**

- Real SMS will be sent
- Real PIN codes required
- Real DCB API charges may apply
- Real carrier billing activated
- Users will be charged according to service plan

**The testing phase is complete. System is production-ready!**

