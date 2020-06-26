#!/usr/local/bin/python3.7

# Install missing packages and then import them
def install_and_import(package, I_Name):


    import importlib
    try:
        importlib.import_module(package)

    except ImportError:
        print(package, "is missing.......")
        print("installing",package, "........")

        import sys, pip
        pip.main(['install', package])

    finally:
        print("Importing packages")
        globals()[I_Name] = importlib.import_module(I_Name)

def main():

    install_and_import('opencv-python', 'cv2')
    install_and_import('numpy', 'numpy')


if __name__ == "__main__":
    main()
