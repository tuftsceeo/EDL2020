import io
import base64
from IPython.display import HTML

def display_mp4(videoPath):
    """
        Name: display_mp4
        Inputs: path to mp4 video
        Returns: HTML for Jupyter notebook to display video
        What it does: Meant to be called in Jupyter,
        displays video in Jupyter Notebook
    """
    video = io.open(videoPath, 'r+b').read()
    encoded = base64.b64encode(video)
    return HTML(data='''<video alt="test" controls>
                    <source src="data:video/mp4;base64,{0}" type="video/mp4" />
                 </video>'''.format(encoded.decode('ascii')))
