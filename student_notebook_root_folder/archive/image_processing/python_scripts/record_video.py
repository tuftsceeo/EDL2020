import cv2
import argparse

def record_from_webcam(outputPath, cameraNumber = 0, maxFrames = 100):
    """
        Name: record_from_webcam
        Inputs: outputPath, cameraNumber = 0, maxFrames = 100
        Return: none
        What it does: records from webcam until the user presses q or maxFrames is reached
    """
    video_capture = cv2.VideoCapture(cameraNumber)
    fourcc = 0x00000021
    out = cv2.VideoWriter(outputPath,fourcc, 20.0, (640,480))
    i = 0
    while True:
        # Capture frame-by-frame
        ret, frame = video_capture.read()
        
        gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)
        out.write(frame)

        cv2.imshow('Video', frame)
        
        i+=1
        if i >= maxFrames:
            print(i)
            break
        if cv2.waitKey(1) & 0xFF == ord('q'):
            break

    # When everything is done, release the capture
    video_capture.release()
    cv2.destroyAllWindows()
    
if __name__ == '__main__':
    parser = argparse.ArgumentParser()
    parser.add_argument("outputPath", help = "path to output video")
    parser.add_argument("-c", "--cameraNumber", help = "camera number")
    args = parser.parse_args()
    camNum = 0
    if args.cameraNumber:
        camNum = args.cameraNumber
    print(args.outputPath, camNum)
    record_from_webcam(args.outputPath, camNum, maxFrames = 10000)
