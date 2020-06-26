#!/usr/local/bin/python3.7

import cv2

# module to reduce the size and shape of the image. Takes image matrix as an input.
def img_resize(img):

    print('Original Dimensions : ',img.shape)

    scale_width_percent = 30000 / img.shape[1]  # percent of original width size
    scale_height_percent = 30000 / img.shape[0] # percent of original height size

    width = int(img.shape[1] * scale_width_percent / 100)
    height = int(img.shape[0] * scale_height_percent / 100)
    dim = (width, height)

    # resize image
    resized = cv2.resize(img, dim, interpolation = cv2.INTER_AREA)

    print('Resized Dimensions : ',resized.shape)

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
    import numpy as numpy
    import argparse

    #**************************** storing image from command line argument ********************************

    ap = argparse.ArgumentParser()

    # takes path of the image as command line argument (after --path)
    ap.add_argument("-i", "--path", required = True, help = "Path to the image")
    args = vars(ap.parse_args())

    # storing img into "image" variable
    image = cv2.imread(args["path"])

    image = img_resize(image)

    #***************************** Image stored inside "image" variable *************************************

    result = noise_remover(image)

    cv2.imwrite('Prep_img.jpg', result)

if __name__ == "__main__":
    main()
