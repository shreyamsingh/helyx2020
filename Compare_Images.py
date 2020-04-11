#compare 2 images
#from skimage import measure
import sys
from skimage.metrics import structural_similarity as ssim
#import matplotlib.pyyplot as plt
import numpy as np
import cv2
import math
import scipy.optimize as op
import os.path as osp

def mean_standard_error(imageA, imageB):
    err = np.sum((imageA.astype("float") - imageB.astype("float")) ** 2)
    err /= float(imageA.shape[0] * imageA.shape[1])
    return err

def compare_images(imageA, imageB, title): #to check if the images are the same
    m = mean_standard_error(imageA, imageB)
    s  = ssim(imageA,imageB)

    fig = plt.figure(title)
    plt.suptitle("MSE: %.2f, SSIM: %.2f" %(m, s))

    #show first image
    ax = fig.add_subplot(1,2,1)
    plt.imshow(imageA, cmap = plt.cm.gray)
    plt.axis("off")

    #show the second image
    ax = fig.add_subplot(1,2,2)
    plt.imshow(imageB, cmap = plt.cm.gray)
    plt.axis("off")

    #shows the plot
    plt.show()

def addTrain(imageA,imageB):
    #1. I need to fix so that checks inside and
    width = np.size(imageA,0)
    height = np.size(imageA,1)
    left =  int(width/4)
    right = int(left * 3)
    upper = int(height/4)
    lower = int(height * 3)

    imgAUntampered = imageA
    imgBUntampered = imageB
    imgAUntampered = cv2.cvtColor(imgAUntampered, cv2.COLOR_BGR2GRAY) #grayscales imgA
    imgBUntampered = cv2.cvtColor(imgBUntampered, cv2.COLOR_BGR2GRAY) #grayscales imgA
    mUntampered = mean_standard_error(imgAUntampered, imgBUntampered)
    sUntampered = ssim(imgAUntampered,imgBUntampered)

    imgACenter = imageA[upper:lower,left:right] #creates the center object from imgA
    imgBCenter = imageB[upper:lower,left:right] #creates the center object from imgB
    imgACenter = cv2.cvtColor(imgACenter, cv2.COLOR_BGR2GRAY) #grayscales imgA
    imgBCenter = cv2.cvtColor(imgBCenter, cv2.COLOR_BGR2GRAY) #grayscales imgB
    mCenter = mean_standard_error(imgACenter, imgBCenter) #finds the mse of the center
    sCenter = ssim(imgACenter,imgBCenter) #finds the ssim of the center

    imageA[upper:lower,left:right] = 0 #sets them middle as empty
    imageB[upper:lower,left:right] = 0 #sets the middle as empty

    imageA = cv2.cvtColor(imageA, cv2.COLOR_BGR2GRAY)
    imageB = cv2.cvtColor(imageB, cv2.COLOR_BGR2GRAY)
    m = mean_standard_error(imageA, imageB)
    s  = ssim(imageA,imageB)

    newInput = np.array([[1,m,s,mCenter,sCenter,mUntampered,sUntampered]])
    #X = np.append(X,newInput,axis = 0)
    #inputs = np.append(X,newInput, axis = 0)
    #outputs = np.append(y,c, axis = 0)
    return newInput #[inputs,outputs]

def featureScale(X):
    ranges = np.ptp(X,axis = 0)
    averages = np.average(X,axis = 0)
    ranges = np.transpose(ranges)
    averages = np.transpose(averages)
    for i in range(np.size(X,1)):
        if ranges[i] > 10:
            X[:,i] -= averages[i]
            X[:,i] /= ranges[i]
    return X, averages, ranges

def featureMap(X,degree):
    newInput = np.array([1])
    for a in range(1,degree):
        for b in range(0,a):
            for c in range(0,b):
                for d in range(0,c):
                    for e in range(0,d):
                        for f in range(0,e):
                            valToAdd = (X[:,0]**(a-b-c-d-e-f)) * (X[:,1]**(b-c-d-e-f)) * (X[:,2]**(c-d-e-f)) * (X[:,3]**(d-e-f)) * (X[:,4]**(e-f)) * (X[:,5]**(f))
                            print('added value')
                            # degreeString = 'a{} b{} c{} d{} e{} f{}'
                            # print(degreeString.format(a,b,c,d,e,f))
                            print(valToAdd)
                            print()
                            newInput = np.hstack((newInput,valToAdd))
    return newInput

def sigmoid(z):
    g = 1.0 / (1.0 + (math.exp(1) ** (-1 * z)))
    return g

def lrCostFunction(theta, X, y, lambd):
    m = np.size(y,0)
    grad = np.zeros(theta.shape)
    h = sigmoid(X.dot(theta))

    J = (1/m) * ((-1 * np.transpose(y)).dot(np.log(h)) - np.transpose(1-y).dot(np.log(1-h)))
    J = J + (lambd/(2*m))*(np.sum(theta * theta - theta[0] * theta[0]));
    return J
    #grad = ((1/m) * np.transpose(X).dot(h - y) + (lambd/m) * theta #check on matrix subtraction

def lrGradientFunction(theta, X, y, lambd, alpha = 10):
    m = np.size(y,0)
    grad = np.zeros(theta.shape)
    h = sigmoid(X.dot(theta))

    grad = np.transpose(X).dot(h - y)
    grad = (alpha/m) * grad
    grad = grad + (lambd/m) * theta
    grad[0] = grad[0] - (lambd/m) * theta[0]
    return grad

def loadData():
    x = np.empty([1,5])
    m = dataSize()
    for i in range(1,m):
        if(i == 1):
            before1 = cv2.imread("/Users/swesikramineni/Desktop/Hackathon/Training_images/Data1/before.jpg")
            after1 = cv2.imread("/Users/swesikramineni/Desktop/Hackathon/Training_images/Data1/after.jpg")
            x = addTrain(before1,after1)
        else:
            beforeStr = "/Users/swesikramineni/Desktop/Hackathon/Training_images/Data{}/before.jpg"
            afterStr = "/Users/swesikramineni/Desktop/Hackathon/Training_images/Data{}/after.jpg"
            before = cv2.imread(beforeStr.format(i))
            after = cv2.imread(afterStr.format(i))
            x = np.append(x,addTrain(before,after),axis = 0)

    #get classification
    f = open("/Users/swesikramineni/Desktop/Hackathon/classification.txt", "r")
    if f.mode == 'r':
        contents = f.readlines()
        y = int(contents[0])
        for i in contents[1:]:
            y = np.row_stack((y,int(i)))
    else:
        print('error: classification text not found')
    return x,y

def dataSize():
    fileStr = "/Users/swesikramineni/Desktop/Hackathon/Training_images/Data{}"
    i = 1
    while(osp.exists(fileStr.format(i))):
        i += 1
    return i


x,y = loadData()
x,mus,ranges = featureScale(x)
print('data before map')
print(x)
print()

x = featureMap(x,6)
print('data after map')
print(x)
print()

init_theta = np.zeros((np.size(x,1),1))
print('before')
print(lrCostFunction(init_theta,x,y,.1))
print()
for i in range(1000000): #can just implemen fmin_ncg for extra accuracy
     init_theta = init_theta - lrGradientFunction(init_theta,x,y,.1,1)
predictions = sigmoid(x.dot(init_theta))

# init_theta = op.fmin_ncg(lrCostFunction, x0 = init_theta, fprime = lrGradientFunction,
# args  = (x,y,.1), maxiter = 1000)
print('after')
print(lrCostFunction(init_theta,x,y,.1))
print()
#print(predictions)

#print(x)
# x = np.array([[1,0,0],[1,1,0],[1,0,1],[1,1,1]]) #testing or function
# y = np.array([[0],[1],[1],[1]]) #testing or function
