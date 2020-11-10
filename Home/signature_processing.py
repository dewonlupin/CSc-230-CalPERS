#!/usr/local/bin/python3.7

import cv2
from skimage.transform import resize
from skimage import io

# module to reduce the size and shape of the image. Takes image matrix as an input.
def img_resize(image):

    height = image.shape[0]
    width  = image.shape[1]
    aspect_ratio =  width / height
    print("Aspect ratio: ", aspect_ratio)
    print("Height:", height)
    print("width:", width)
    #for square-like images
    if aspect_ratio < 1.3:
        resized = resize(image, (300, 300), order=1, preserve_range=True,  clip= True, anti_aliasing=True)
    #for rectangle-like images
    else:
        resized = resize(image, (250, 500), order=1, preserve_range=True,  clip= True, anti_aliasing=True)

    return resized




def noise_remover(img):
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    blur = cv2.GaussianBlur(gray, (3,3), 0)
    thresh = cv2.threshold(blur, 0, 255, cv2.THRESH_BINARY_INV + cv2.THRESH_OTSU)[1]

    # Bitwise-and and color background white
    result = cv2.bitwise_and(img, img, mask=thresh)
    result[thresh==0] = [255,255,255] # NOw making black background to white(give original image)

    '''
    cv2.imshow('result', result)
    cv2.waitKey(0)
    cv2.destroyAllWindows()
    '''

    return result



def main():

    import cv2
    import argparse
    from skimage import img_as_ubyte
    from skimage.util import img_as_float

    #**************************** storing image from command line argument ********************************
    ap = argparse.ArgumentParser()

    # takes path of the image as command line argument (after --path)
    ap.add_argument("-i", "--path", required = True, help = "Path to the image")
    args = vars(ap.parse_args())

    # storing img into "image" variable
    image = cv2.imread(args["path"])




    #***************************** Image stored inside "image" variable *************************************
    #image = cv2.imread("/Users/sarthakbhatt/Desktop/img2.png")

    pure = noise_remover(image)
    #pure = img_as_float(pure)
    cv2.imwrite("prep.png",pure)
    prep_image =  io.imread(fname="prep.png", as_gray=False)
    result = img_resize(prep_image)

    io.imsave('Prep_img.png', img_as_float(result))

    #io.imshow(result)

if __name__ == "__main__":
    main()
