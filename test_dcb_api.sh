#!/bin/bash

# DCB API Testing Script
# Usage: ./test_dcb_api.sh

BASE_URL="http://quickfun.mediaworldsdp.com/api/dcb"

echo "=========================================="
echo "DCB API Testing"
echo "=========================================="
echo ""

# Test 1: Get available services
echo "1. Testing GET /api/dcb/services"
echo "-------------------------------------------"
curl -X GET "${BASE_URL}/services" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -w "\n\nHTTP Status: %{http_code}\n" \
  | jq . 2>/dev/null || cat
echo ""
echo ""

# Test 2: Send PIN code
echo "2. Testing POST /api/dcb/send-pincode"
echo "-------------------------------------------"
echo "Request: Sending PIN to 9647701234567"
curl -X POST "${BASE_URL}/send-pincode" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "msisdn": "9647701234567",
    "service": "dcb_mediaworld"
  }' \
  -w "\n\nHTTP Status: %{http_code}\n" \
  | jq . 2>/dev/null || cat
echo ""
echo ""

# Test 3: Verify PIN code
echo "3. Testing POST /api/dcb/verify-pincode"
echo "-------------------------------------------"
echo "Request: Verifying PIN 1234 for 9647701234567"
curl -X POST "${BASE_URL}/verify-pincode" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "msisdn": "9647701234567",
    "pincode": "1234",
    "service": "dcb_mediaworld"
  }' \
  -w "\n\nHTTP Status: %{http_code}\n" \
  | jq . 2>/dev/null || cat
echo ""
echo ""

# Test 4: Invalid phone number
echo "4. Testing validation (invalid phone)"
echo "-------------------------------------------"
curl -X POST "${BASE_URL}/send-pincode" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "msisdn": "123456",
    "service": "dcb_mediaworld"
  }' \
  -w "\n\nHTTP Status: %{http_code}\n" \
  | jq . 2>/dev/null || cat
echo ""
echo ""

echo "=========================================="
echo "Testing Complete"
echo "=========================================="

