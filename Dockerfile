FROM ubuntu:latest
LABEL authors="flic"

ENTRYPOINT ["top", "-b"]
