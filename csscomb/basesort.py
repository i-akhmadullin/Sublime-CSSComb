import threading
import urllib2


class BaseSort(threading.Thread):

    def __init__(self, sel, original, sortorder):
        self.sel = sel
        self.original = original
        self.sortorder = sortorder
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
            self.result = 'Some OSError'
        except (urllib2.HTTPError) as (e):
            self.error = True
            self.result = 'CSScomb Error: HTTP error %s contacting API' % (str(e.code))
        except (urllib2.URLError) as (e):
            self.error = True
            self.result = 'CSScomb Error: ' + str(e.reason)
