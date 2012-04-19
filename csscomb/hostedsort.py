import sublime
import sublime_plugin
import sys,subprocess
from os import path

from basesort import BaseSort

__file__ = path.normpath(path.abspath(__file__))
__path__ = path.dirname(__file__)
libs_path = path.join(__path__, 'libs')
csscomb_path = path.join(libs_path, 'call_string.php')

class HostedSort(BaseSort):

    def exec_request(self):
        myprocess = subprocess.Popen(['php', csscomb_path, self.original], shell=False, stdout=subprocess.PIPE)
        (sout, serr) = myprocess.communicate()
        myprocess.wait()

        if len(sout) > 0:
            return sout
        else:
            return None

    def run(self):
        try:
            self.result = self.exec_request()
        except (OSError) as (e):
             self.error = True
             self.result = 'Sorter Error: attempt to sort non-existent file'