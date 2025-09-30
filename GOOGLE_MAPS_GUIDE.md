# 📍 Google Maps Embed Guide for Monument System

## 🎯 Overview
This guide shows you how to add interactive Google Maps to your monuments using the embed feature.

## 🚀 Quick Steps

### 1. Go to Google Maps
- Open [Google Maps](https://maps.google.com) in your browser
- Make sure you're signed in to your Google account

### 2. Search for Your Monument
- Type the monument name or address in the search box
- Example: "Angkor Wat, Siem Reap, Cambodia"
- Click on the location to select it

### 3. Get the Embed Code
1. Click the **"Share"** button (usually near the location name)
2. Select **"Embed a map"** tab
3. Choose your preferred size:
   - **Small**: 400x300
   - **Medium**: 600x450 (Recommended)
   - **Large**: 800x600
   - **Custom**: Set your own dimensions

### 4. Copy the Embed Code
- Copy the entire `<iframe>` code that appears
- It should look like this:
```html
<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3908.7..." 
        width="600" height="450" style="border:0;" 
        allowfullscreen="" loading="lazy" 
        referrerpolicy="no-referrer-when-downgrade">
</iframe>
```

### 5. Paste in Monument Form
- Go to your monument create/edit form
- Find the **"Google Maps Embed"** field
- Paste the entire iframe code there
- Save your monument

## ✅ What You Get

### Before (Without Embed):
- Basic address text
- Auto-generated map (may not be accurate)

### After (With Embed):
- Interactive Google Maps
- Street View available
- Zoom in/out functionality
- Satellite/terrain views
- Accurate location pinpointing

## 🎨 Customization Options

### Map Size
You can modify the width and height in the iframe:
```html
<iframe src="..." width="800" height="600" ...></iframe>
```

### Map Type
Add parameters to the URL for different views:
- `&maptype=satellite` - Satellite view
- `&maptype=terrain` - Terrain view
- `&maptype=hybrid` - Hybrid view

### Zoom Level
Add `&z=15` to control zoom (1-20, where 20 is closest)

## 🔧 Troubleshooting

### Map Not Showing?
1. **Check the iframe code** - Make sure it's complete
2. **Verify the URL** - Should start with `https://www.google.com/maps/embed`
3. **Test the link** - Open the src URL in a new tab

### Wrong Location?
1. **Search more specifically** - Include city, province, country
2. **Use coordinates** - Search by latitude,longitude
3. **Drop a pin manually** - Right-click on the exact spot

### Size Issues?
1. **Adjust iframe dimensions** - Modify width/height attributes
2. **Use responsive sizing** - Set width="100%" for mobile-friendly maps

## 📱 Mobile Optimization

For better mobile experience, use:
```html
<iframe src="..." 
        width="100%" height="300" 
        style="border:0; max-width: 600px;" 
        allowfullscreen="" loading="lazy">
</iframe>
```

## 🎯 Best Practices

### ✅ Do:
- Use medium size (600x450) for best balance
- Include specific location details in search
- Test the map before saving
- Use loading="lazy" for better performance

### ❌ Don't:
- Use extremely large maps (slows page loading)
- Forget to include allowfullscreen attribute
- Use maps without proper location verification

## 🔗 Alternative: Coordinates Method

If you know exact coordinates:
1. Go to Google Maps
2. Search: `13.4125, 103.8667` (latitude, longitude)
3. Follow the same embed process

## 📞 Support

If you need help:
1. Check this guide first
2. Test with a simple location (like "Angkor Wat")
3. Contact the development team with:
   - Monument name
   - Location you're trying to embed
   - Error message (if any)

---

**Happy Mapping! 🗺️**
