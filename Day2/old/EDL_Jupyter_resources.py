import os, sys
import subprocess

#resources file for EDL jupyter notebooks
#2020

#hides console output for some gopigo methods that like to be very verbose
class HiddenPrints:
    def __enter__(self):
        self._original_stdout = sys.stdout
        sys.stdout = open(os.devnull, 'w')

    def __exit__(self, exc_type, exc_val, exc_tb):
        sys.stdout.close()
        sys.stdout = self._original_stdout

#stops the pi from speaking its ip all the time
def stop_speaking_ip():
    cmd=['ps','aux']
    output=subprocess.Popen(cmd,stdout=subprocess.PIPE).communicate()
    output=str(output[0]).split('\\n')
    processesToKill=[]
    for p in output:
        if 'aud.sh'  in p: #this is the file that makes it speak its ip
            processesToKill.append(p)
    if len(processesToKill)>0: #if it is running
        for p in processesToKill:
            info = p.split(' ') #kill all of its processes
            for entry in info:
                if entry.isdigit():
                    os.system('kill %d'%(int(entry)))
                    break
        print('Pi is no longer speaking its ip address, restart the pi to have it speak the address again.')
    else:
        print('Pi is not speaking its ip address, restart the pi to have it speak the address again.')