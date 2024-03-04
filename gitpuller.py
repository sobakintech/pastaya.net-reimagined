import subprocess
import time

def git_pull():
    try:
        subprocess.run(["git", "pull"], check=True)
        print("success lel")
    except subprocess.CalledProcessError as e:
        print("oh no fuck you heres an error:", e)

if __name__ == "__main__":
    while True:
        git_pull()
        time.sleep(300) # this is like 5 mins
