import sublime
import sublime_plugin
import threading
import urllib2

class BaseSort(threading.Thread):

    def __init__(self, sel, original):
        self.sel = sel
        self.original = original
        self.result = None
        self.error = None
        threading.Thread.__init__(self)

    def exec_request(self):
        return

    def run(self):
        try:
            self.result = self.exec_request()
        except (OSError) as (e):
             self.error = True
             self.result = 'CSScomb Error: attempt to sort non-existent file'
        except (urllib2.HTTPError) as (e):
            self.error = True
            self.result = 'CSScomb Error: HTTP error %s contacting API' % (str(e.code))
        except (urllib2.URLError) as (e):
            self.error = True
            self.result = 'CSScomb Error: ' + str(e.reason)