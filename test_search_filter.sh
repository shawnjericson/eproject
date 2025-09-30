#!/bin/bash

# Test Search and Filter System
# This script tests the improved search and filter functionality

echo "=================================="
echo "Testing Search and Filter System"
echo "=================================="
echo ""

BASE_URL="http://localhost:8000/api"

echo "1. Testing Monuments API"
echo "------------------------"

echo "Test 1.1: No parameters (should return all monuments)"
curl -s "${BASE_URL}/monuments" | jq '.data | length'
echo ""

echo "Test 1.2: Only search parameter"
curl -s "${BASE_URL}/monuments?search=temple" | jq '.data | length'
echo ""

echo "Test 1.3: Only zone filter"
curl -s "${BASE_URL}/monuments?zone=East" | jq '.data | length'
echo ""

echo "Test 1.4: Search + Zone filter"
curl -s "${BASE_URL}/monuments?search=temple&zone=East" | jq '.data | length'
echo ""

echo "Test 1.5: Empty search (should return all)"
curl -s "${BASE_URL}/monuments?search=" | jq '.data | length'
echo ""

echo "Test 1.6: Empty zone (should return all)"
curl -s "${BASE_URL}/monuments?zone=" | jq '.data | length'
echo ""

echo ""
echo "2. Testing Posts API"
echo "--------------------"

echo "Test 2.1: No parameters"
curl -s "${BASE_URL}/posts" | jq '.data | length'
echo ""

echo "Test 2.2: Only search parameter"
curl -s "${BASE_URL}/posts?search=travel" | jq '.data | length'
echo ""

echo "Test 2.3: Empty search (should return all)"
curl -s "${BASE_URL}/posts?search=" | jq '.data | length'
echo ""

echo ""
echo "3. Testing Feedbacks API"
echo "------------------------"

echo "Test 3.1: No parameters"
curl -s "${BASE_URL}/feedbacks" | jq '.data | length'
echo ""

echo "Test 3.2: Only search parameter"
curl -s "${BASE_URL}/feedbacks?search=great" | jq '.data | length'
echo ""

echo "Test 3.3: Only monument_id filter"
curl -s "${BASE_URL}/feedbacks?monument_id=1" | jq '.data | length'
echo ""

echo "Test 3.4: Search + monument_id filter"
curl -s "${BASE_URL}/feedbacks?search=great&monument_id=1" | jq '.data | length'
echo ""

echo ""
echo "4. Testing Gallery API"
echo "----------------------"

echo "Test 4.1: No parameters"
curl -s "${BASE_URL}/gallery" | jq '.data | length'
echo ""

echo "Test 4.2: Only search parameter"
curl -s "${BASE_URL}/gallery?search=sunset" | jq '.data | length'
echo ""

echo "Test 4.3: Only monument_id filter"
curl -s "${BASE_URL}/gallery?monument_id=1" | jq '.data | length'
echo ""

echo "Test 4.4: Search + monument_id filter"
curl -s "${BASE_URL}/gallery?search=sunset&monument_id=1" | jq '.data | length'
echo ""

echo ""
echo "=================================="
echo "Testing Complete!"
echo "=================================="
echo ""
echo "Expected behavior:"
echo "- Tests with empty parameters should return same as no parameters"
echo "- Tests with search should return filtered results"
echo "- Tests with filters should return filtered results"
echo "- Tests with search + filters should return results matching ALL conditions"
echo ""

