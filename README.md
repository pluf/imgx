# Pluf ImgX

Images present a slightly complicated case when it comes to delivering the perfect variation on each 
device using a CDN.
You need to consider the image format, image dimensions, image compression, aspect ratio, and a lot more, 
while balancing the image's visual quality. 
Therefore, we need to have a CDN tuned for image delivery. ImgX is the solution.

ImgX is a regular content delivery network topped with a set of software enhancements to enhance the
underlying CDNs functionality for optimizing and transforming images in real-time, thereby making it
more suitable for image delivery.

This is the most important thing to understand about all the image CDNs out there. They are not an entirely 
different content delivery network; they are built on top of existing ones with software enhancements required 
for image optimization.

The Processing
As mentioned earlier, we need to add to the content delivery network the unique functionality required for modifying and optimizing the images. This processing functionality would allow you to resize images, crop them, watermark them, and apply visual effects to them.

This functionality is performed via dedicated "processing servers," which are separate from the servers in the content delivery network. The processing servers are the ones doing the heavy lifting in the image CDN setup. These would always be far fewer in number than the servers in a content delivery network and localized in a few regions.

## The Storage

ImgX optimizes and transferes on any image, even the ones that are outside of the image CDN's system.

ImgX comes with a storage or a media library for users to upload and manage images.

These uploaded images can be accessed via the CDN, and can be processed by the processing part of the image CDN. 

This media library makes it simple to get started with using the service apart from simplifying image upload and management.

## The Processing

ImgX adds to the content delivery network the unique functionality required for modifying and optimizing the images. 

This processing functionality allow you to resize images, crop them, watermark them, and apply visual effects to them.

This functionality is performed via dedicated "processing servers," which are separate from the servers in the content
delivery network.

The processing servers are the ones doing the heavy lifting in the image CDN setup. These would always be far fewer in
number than the servers in a content delivery network and localized in a few regions.

## Quick start

To run the latest versio of the ImgX execute the following command:

	docker run \
		-p "80:80" \
		-e "IMGX_ALLOWED_HOST=*" \
		viraweb/imgx:latest

And use CURL to check the server:

	curl \
		--output logo-h100-w100 \
		http://localhost/https://viraweb123.ir/api/v2/cms/contents/logo/content?w=100&h=100

## Report bugs

To report a bug, make an issue on the [github](https://github.com/viraweb/imgx) project
