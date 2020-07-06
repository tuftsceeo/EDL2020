import argparse
import cv2

def display_video(videoPath):
    """
        Name: display_video
        Inputs: path to video
        Returns: none
        What it does: opens an opencv window display the video
    """
    cap = cv2.VideoCapture(videoPath)

    while(True):
        ret, frame = cap.read()
        if ret:
            lframe = frame
            cv2.imshow('frame',frame)
        else:
            cv2.imshow('frame', lframe)
        if cv2.waitKey(1) & 0xFF == ord('q'):
            break

    cap.release()
    cv2.destroyAllWindows()

if __name__ == "__main__":
    parser = argparse.ArgumentParser()
    parser.add_argument("recordingPath", help = "path to video")
    args = parser.parse_args()
    display_video(args.recordingPath)
    