import cv2
import argparse
import numpy as np

def adjacent_video(videoPath1, videoPath2, outputPath):
    """
        Name: adjacent_video
        Input: path to left video, path to right video, path to output
        Return: none
        What it does: puts left and right video sidebyside
    """
    # read existing video in folder
    cap = cv2.VideoCapture(videoPath1)
    cap2 = cv2.VideoCapture(videoPath2)

    # output file
    fourcc = 0x00000021
    out = cv2.VideoWriter(outputPath, fourcc, 20.0, (1280,480))

    while(True):
        ret, frame = cap.read()
        ret2, frame2 = cap2.read()
        if not ret and not ret2:
            break;
        if ret:
            lframe = frame
        if ret:
            lframe2 = frame2
        #merge images side by side    
        combined_frame = np.concatenate([lframe, lframe2], axis = 1)
        out.write(combined_frame)

    cap.release()
    cap2.release()
    cv2.destroyAllWindows()

if __name__ == '__main__':
    parser = argparse.ArgumentParser()
    parser.add_argument("inputPath", help = "path to video with *'XVID' codec")
    parser.add_argument("inputPath2", help = "path to video with *'XVID' codec")
    parser.add_argument("outputPath", help = "path to output video with faces and eyes detected")
    args = parser.parse_args()
    adjacent_video(args.inputPath, args.inputPath2, args.outputPath)
