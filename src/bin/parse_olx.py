#!/usr/bin/env python3

import requests
import sys
from bs4 import BeautifulSoup
from pathlib import Path

URL = sys.argv[1]
HEADERS = {
    'accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
    'user-agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36'
}

if URL is None:
    exit(1)

def get_html(url, params=''):
    response = requests.get(url, headers=HEADERS, params=params)
    
    return response

def get_price(html):
    soup = BeautifulSoup(html, 'html.parser')

    return soup.select_one('[data-testid="ad-price-container"] h3').text

# For testing you can use local storage
# html = Path('example.html').read_text()

# or you can use real website
html = get_html(URL)

print(get_price(html))
