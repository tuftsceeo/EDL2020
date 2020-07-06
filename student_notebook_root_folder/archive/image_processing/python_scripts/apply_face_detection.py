
import cv2
import argparse

def detect_face(img, faceCascade, eyeCascade):
    """
        Name: detect_face
        Input: image(numpy 3d array), face classifier, eye classifier
        Return: n/a
        What it does: draws red box around face, draws green box around eye
    """
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    faces = faceCascade.detectMultiScale(
        gray,
        scaleFactor=1.1,
        minNeighbors=5,
        minSize=(30, 30),
        flags= cv2.CASCADE_SCALE_IMAGE
    )
    # Draw a rectangle around the faces
    for (x, y, w, h) in faces:
        cv2.rectangle(img, (x, y), (x+w, y+h), (0, 0, 255), 2)
        # roi stands for region of interest-- eyes always on the face
        roi_gray = gray[y:y+h, x:x+w]
        roi_color = img[y:y+h, x:x+w]
        eyes = eyeCascade.detectMultiScale(roi_gray)
        for (ex,ey,ew,eh) in eyes:
            cv2.rectangle(roi_color,(ex,ey),(ex+ew,ey+eh),(0,255,0),2)
def face_detect_video(inputVideoPath, outputVideoPath):
    """
        Name: face_detect_video
        Input: path to video, path to output
        Return: none
        What it does: detects faces and eyes for an entire video
    """
    # read existing video in folder
    cap = cv2.VideoCapture(inputVideoPath)

    # use face detection classifier in folder
    # it's good practice to have paths be variables so they are easily edited
    cascPath = '../haarcascades/lbpcascade_frontalface_improved.xml'
    eyeCascPath = '../haarcascades/haarcascade_eye.xml'
    faceCascade = cv2.CascadeClassifier(cascPath)
    eyeCascade = cv2.CascadeClassifier(eyeCascPath)

    # output file
    fourcc = 0x00000021
    out = cv2.VideoWriter(outputVideoPath, fourcc, 20.0, (640,480))

    i = 0
    while(cap.isOpened()):
        if i %40 ==0:
            print(i)
        i +=1
        ret, frame = cap.read()
        if ret is False:
            break
        detect_face(frame, faceCascade, eyeCascade)
        out.write(frame)

    cap.release()
    cv2.destroyAllWindows()
    
if __name__ == "__main__":
    parser = argparse.ArgumentParser()
    parser.add_argument("inputPath", help = "path to video")
    parser.add_argument("outputPath", help = "path to output as mp4")
    args = parser.parse_args()
    face_detect_video(args.inputPath, args.outputPath)


